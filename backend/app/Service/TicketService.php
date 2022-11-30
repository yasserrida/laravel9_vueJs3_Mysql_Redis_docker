<?php

namespace App\Service;

use App\Events\TicketSupportAdminEvent;
use App\Events\TicketSupportResoluEvent;
use App\Models\User;
use App\Models\Ticket;
use App\Service\DocumentsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    protected DocumentsService $DocumentsService;

    public function __construct(DocumentsService $DocumentsService)
    {
        $this->DocumentsService = $DocumentsService;
    }

    public function getAll(array $params)
    {
        $tickets = Ticket::select('tickets.*')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->when(isset($params['priority']), fn ($query) => $query->where('tickets.priority', $params['priority']))
            ->when(isset($params['statut']), fn ($query) => $query->where('tickets.statut', $params['statut']))
            ->when(isset($params['label']), fn ($query) => $query->where('tickets.label', $params['label']))
            ->when(isset($params['categorie']), fn ($query) => $query->where('tickets.categorie', $params['categorie']))
            ->when(isset($params['is_resolved']), fn ($query) => $query->where('tickets.is_resolved', $params['is_resolved']));

        $user = User::find(Auth::guard('api')->id());

        if ($user->hasRole('ADMINISTRATEUR')) {
        } elseif ($user->hasRole('RESPONSABLE')) {
            $tickets = $tickets->where(
                fn ($query) =>
                $query->where('tickets.user_id', $user->id)->orwhere('users.owner', $user->id)
            );
        } else {
            $tickets = $tickets->where('tickets.user_id', $user->id);
        }

        $tickets = (isset($params['sort']) && isset($params['sordOrder']))
            ? $tickets->orderBy('tickets.' . $params['sort'], $params['sordOrder'])
            : $tickets->orderBy('tickets.updated_at', 'DESC');

        return $tickets->orderBy('id', 'ASC')->paginate(8);
    }

    public function get(string $id): Ticket
    {
        return Ticket::findOrFail($id);
    }

    public function getDocuments(string $id): Collection
    {
        return $this->DocumentsService->getDocuments('ticket_id', $id, 'tickets');
    }

    public function store(Request $request): Ticket
    {
        $data = $request->all();

        $ticket = Ticket::create($data);

        $this->DocumentsService->storeMultiple($request, 'ticket_id', strval($ticket['id']), 'tickets', strval($ticket['categorie']));

        TicketSupportAdminEvent::dispatch($ticket);

        return $ticket;
    }

    public function update(Request $request, string $id): Ticket
    {
        $data = $request->all();

        $ticket = Ticket::findOrFail($id);

        if ($ticket->is_resolved == 0 && isset($data['is_resolved']) && $data['is_resolved'] == 1) {
            $data['statut'] = 'CLOSED';
            TicketSupportResoluEvent::dispatch($ticket, $ticket['user_id']);
        }

        $ticket->update($data);

        $this->DocumentsService->updateMultiple($request, 'ticket_id', strval($data['id']), 'tickets', strval($data['categorie']));

        return $ticket;
    }

    public function delete(string $id): void
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
    }
}
