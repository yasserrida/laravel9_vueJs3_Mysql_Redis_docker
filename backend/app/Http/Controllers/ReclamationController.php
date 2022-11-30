<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Service\ReclamationService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReclamationController extends Controller
{
    protected ReclamationService $ReclamationService;

    public function __construct(ReclamationService $ReclamationService)
    {
        $this->ReclamationService = $ReclamationService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            return response()->json($this->ReclamationService->getAll($request->all()), 200);
        } catch (Exception $e) {
            Log::channel('reclamations')->error('Index : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->authorize('create', Reclamation::class);

            return response()->json($this->ReclamationService->store($request), 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (Exception $e) {
            Log::channel('reclamations')->error('Store : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            return response()->json($this->ReclamationService->get($id), 200);
        } catch (ModelNotFoundException $e) {
            Log::channel('reclamations')->error('Show : '.$e->getMessage());

            return response()->json(['message' => 'Reclamation introuvable'], 500);
        } catch (Exception $e) {
            Log::channel('reclamations')->error('Show : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $this->authorize('update', Reclamation::findOrFail($id));

            return response()->json($this->ReclamationService->update($request->all(), $id), 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (ModelNotFoundException $e) {
            Log::channel('reclamations')->error('Update : '.$e->getMessage());

            return response()->json(['message' => 'Reclamation introuvable'], 500);
        } catch (Exception $e) {
            Log::channel('reclamations')->error('Update : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
