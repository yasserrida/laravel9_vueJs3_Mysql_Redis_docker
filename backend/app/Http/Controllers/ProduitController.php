<?php

namespace App\Http\Controllers;

use App\Service\ProduitService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProduitController extends Controller
{
    protected ProduitService $ProduitService;

    public function __construct(ProduitService $ProduitService)
    {
        $this->ProduitService = $ProduitService;
    }

    public function index(): JsonResponse
    {
        try {
            return response()->json($this->ProduitService->getAll(), 200);
        } catch (Exception $e) {
            Log::error('Produits : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            return response()->json($this->ProduitService->get($id), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Produits : ' . $e->getMessage());

            return response()->json(['message' => 'Produits introuvable'], 500);
        } catch (Exception $e) {
            Log::error('Produits : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->authorize('isResponsable');

            return response()->json($this->ProduitService->store($request->all()), 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (Exception $e) {
            Log::error('Produits : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $this->authorize('isResponsable');

            return response()->json($this->ProduitService->update($request->all(), $id), 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (ModelNotFoundException $e) {
            Log::error('Produits : ' . $e->getMessage());

            return response()->json(['message' => 'Produits introuvable'], 500);
        } catch (Exception $e) {
            Log::error('Produits : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function delete(string $id): JsonResponse
    {
        try {
            $this->ProduitService->delete($id);

            return response()->json(true, 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Produits : ' . $e->getMessage());

            return response()->json(['message' => 'Produits introuvable'], 500);
        } catch (Exception $e) {
            Log::error('Produits : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
