<?php

namespace App\Service;

use App\Events\NotificationUserCreatedEvent;
use App\Models\NotificationUser;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class NotificationUserService
{
    public function getAll(array $params): LengthAwarePaginator
    {
        $notifications = NotificationUser::select(
            'notifications_user.id',
            'notifications_user.name',
            'notifications_user.status',
            'notifications_user.temps_traitement',
            'users.name as gestionnaire'
        )
            ->leftjoin('users', 'users.id', '=', 'notifications_user.user_id')
            ->where('archiver', false)
            ->when(isset($params['id']), fn ($query) => $query->where('notifications_user.id', $params['id']))
            ->when(isset($params['name']), fn ($query) => $query->where('notifications_user.name', 'REGEXP', $params['name']));

        $user = User::find(Auth::guard('api')->id());
        if ($user->hasRole('GESTIONNAIRE')) {
            $notifications = $notifications->where('notifications_user.status', true)->where('notifications_user.user_id', $user->owner);
        } elseif ($user->hasRole('RESPONSABLE')) {
            $notifications = $notifications->where('notifications_user.user_id', $user->id);
        }

        $notifications = (isset($params['sort']) && isset($params['sordOrder']))
            ? $notifications->orderBy('notifications_user.' . $params['sort'], $params['sordOrder'])
            : $notifications->orderBy('notifications_user.updated_at', 'DESC');

        return $notifications->paginate(8);
    }

    public function get(string $id): NotificationUser
    {
        return NotificationUser::with('personne')->findOrFail($id);
    }

    public function store(array $data): NotificationUser
    {
        $notification = NotificationUser::create($data);

        if ($data['status']) {
            NotificationUserCreatedEvent::dispatch($notification);
        }

        return $notification;
    }

    public function update(array $data, string $id): NotificationUser
    {
        $notification = NotificationUser::findOrFail($id);
        $notification->update($data);

        if ($data['status']) {
            NotificationUserCreatedEvent::dispatch($notification);
        }

        return $notification;
    }

    public function delete(string $id): void
    {
        $notification = NotificationUser::findOrFail($id);
        $notification->update([
            'archiver' => true,
        ]);
    }
}
