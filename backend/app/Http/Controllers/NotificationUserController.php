<?php

namespace App\Http\Controllers;

use App\Models\NotificationUser;
use App\Service\NotificationUserService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationUserController extends Controller
{
    protected NotificationUserService $NotificationUserService;

    public function __construct(NotificationUserService $NotificationUserService)
    {
        $this->NotificationUserService = $NotificationUserService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            return response()->json($this->NotificationUserService->getAll($request->all()), 200);
        } catch (Exception $e) {
            Log::channel('notifications')->error('Index : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->authorize('create', NotificationUser::class);

            return response()->json($this->NotificationUserService->store($request->all()), 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (ModelNotFoundException $e) {
            Log::channel('notifications')->error('Store : '.$e->getMessage());

            return response()->json(['message' => 'Notification introuvable'], 500);
        } catch (Exception $e) {
            Log::channel('notifications')->error('Store : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            return response()->json($this->NotificationUserService->get($id), 200);
        } catch (ModelNotFoundException $e) {
            Log::channel('notifications')->error('Show : '.$e->getMessage());

            return response()->json(['message' => 'Notification introuvable'], 500);
        } catch (Exception $e) {
            Log::channel('notifications')->error('Show : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $this->authorize('update', NotificationUser::findOrFail($id));

            return response()->json($this->NotificationUserService->update($request->all(), $id), 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (ModelNotFoundException $e) {
            Log::channel('notifications')->error('Update : '.$e->getMessage());

            return response()->json(['message' => 'Notification introuvable'], 500);
        } catch (Exception $e) {
            Log::channel('notifications')->error('Update : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function delete(string $id): JsonResponse
    {
        try {
            $this->authorize('delete', NotificationUser::findOrFail($id));

            $this->NotificationUserService->delete($id);

            return response()->json(['message' => 'success'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (ModelNotFoundException $e) {
            Log::channel('notifications')->error('Delete : '.$e->getMessage());

            return response()->json(['message' => 'Notification introuvable'], 500);
        } catch (Exception $e) {
            Log::channel('notifications')->error('Delete : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
