<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\UserService;
use ErrorException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        try {
            return response()->json($this->userService->getAll(), 200);
        } catch (Exception $e) {
            Log::channel('users')->error('Index : '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->authorize('create', User::class);

            return response()->json($this->userService->store($request->all()), 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (Exception $e) {
            Log::channel('users')->error('Store : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $this->authorize('update', User::class);

            return response()->json($this->userService->update($request->all(), $id), 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (ModelNotFoundException $e) {
            Log::channel('users')->error('Update : '.$e->getMessage());

            return response()->json(['message' => 'User introuvable'], 500);
        } catch (ErrorException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            Log::channel('users')->error('Update : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function getUser(): JsonResponse
    {
        try {
            return response()->json($this->userService->getUser(), 200);
        } catch (ModelNotFoundException $e) {
            Log::channel('users')->error('Get User : '.$e->getMessage());

            return response()->json(['message' => 'Session Invalid'], 500);
        } catch (ErrorException $e) {
            Log::channel('users')->error('Get User : '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            Log::channel('users')->error('Get User : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function getUserPermissions(string $id): JsonResponse
    {
        try {
            return response()->json($this->userService->getUserPermissions($id), 200);
        } catch (Exception $e) {
            Log::channel('users')->error('Get User Permissions : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function updateSelf(Request $request): JsonResponse
    {
        try {
            return response()->json($this->userService->updateSelf($request->all()), 200);
        } catch (ModelNotFoundException $e) {
            Log::channel('users')->error('Update Self : '.$e->getMessage());

            return response()->json(['message' => 'Session Invalid'], 500);
        } catch (ErrorException $e) {
            Log::channel('users')->error('Update Self : '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            Log::channel('users')->error('Update Self : '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getResponsables(): JsonResponse
    {
        try {
            return response()->json($this->userService->getResponsables(), 200);
        } catch (Exception $e) {
            Log::channel('users')->error('Get Responsables : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function getGestionnaires(): JsonResponse
    {
        try {
            return response()->json($this->userService->getGestionnaires(), 200);
        } catch (Exception $e) {
            Log::channel('users')->error('Get Gestionnaires : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
