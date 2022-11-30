<?php

namespace App\Http\Controllers;

use App\Service\TicketService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    protected TicketService $TicketService;

    public function __construct(TicketService $TicketService,)
    {
        $this->TicketService = $TicketService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            return response()->json($this->TicketService->getAll($request->all()), 200);
        } catch (Exception $e) {
            Log::error('Tickets : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {

            return response()->json($this->TicketService->store($request), 200);
        } catch (Exception $e) {
            Log::error('Tickets : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            return response()->json($this->TicketService->get($id), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Tickets : ' . $e->getMessage());

            return response()->json(['message' => 'Ticket introuvable'], 500);
        } catch (Exception $e) {
            Log::error('Tickets : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            return response()->json($this->TicketService->update($request, $id), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Tickets : ' . $e->getMessage());

            return response()->json(['message' => 'Ticket introuvable'], 500);
        } catch (Exception $e) {
            Log::error('Tickets : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function delete(string $id): JsonResponse
    {
        try {
            $this->TicketService->delete($id);

            return response()->json(['message' => 'success'], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Tickets : ' . $e->getMessage());

            return response()->json(['message' => 'Ticket introuvable'], 500);
        } catch (Exception $e) {
            Log::error('Tickets : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function getDocuments(string $id): JsonResponse
    {
        try {
            return response()->json($this->TicketService->getDocuments($id), 200);
        } catch (Exception $e) {
            Log::error('Tickets : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
