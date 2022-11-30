<?php

namespace App\Http\Controllers;

use App\Service\GammeService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GammeController extends Controller
{
    protected GammeService $GammeService;

    public function __construct(GammeService $GammeService)
    {
        $this->GammeService = $GammeService;
    }

    public function index(): JsonResponse
    {
        try {
            return response()->json($this->GammeService->getAll(), 200);
        } catch (Exception $e) {
            Log::error('Gammes : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->authorize('isResponsable');

            return response()->json($this->GammeService->store($request->all()), 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (Exception $e) {
            Log::error('Gammes : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            return response()->json($this->GammeService->get($id), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Gammes : ' . $e->getMessage());

            return response()->json(['message' => 'Gamme introuvable'], 500);
        } catch (Exception $e) {
            Log::error('Gammes : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $this->authorize('isResponsable');

            return response()->json($this->GammeService->update($request->all(), $id), 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (ModelNotFoundException $e) {
            Log::error('Gammes : ' . $e->getMessage());

            return response()->json(['message' => 'Gamme introuvable'], 500);
        } catch (Exception $e) {
            Log::error('Gammes : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function delete(string $id): JsonResponse
    {
        try {
            $this->GammeService->delete($id);

            return response()->json(['message' => 'success'], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Gammes : ' . $e->getMessage());

            return response()->json(['message' => 'Gamme introuvable'], 500);
        } catch (Exception $e) {
            Log::error('Gammes : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
