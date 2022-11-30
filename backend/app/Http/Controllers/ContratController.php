<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Service\ContartService;
use App\Service\DocumentsService;
use ErrorException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContratController extends Controller
{
    protected ContartService $ContartService;

    protected DocumentsService $documentService;

    public function __construct(ContartService $ContartService, DocumentsService $DocumentService)
    {
        $this->ContartService = $ContartService;
        $this->documentService = $DocumentService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $contrats = $this->ContartService->getAll($request->all());

            return response()->json($contrats);
        } catch (Exception $e) {
            Log::channel('contrats')->error('Index : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $contrat = $this->ContartService->getById($id);

            return response()->json($contrat);
        } catch (ModelNotFoundException $e) {
            Log::channel('contrats')->error('Show : '.$e->getMessage());

            return response()->json(['message' => 'Contrat introuvable'], 500);
        } catch (Exception $e) {
            Log::channel('contrats')->error('Show : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function getByNum(string $id): JsonResponse
    {
        try {
            $contrat = $this->ContartService->getByNum($id);

            return response()->json($contrat);
        } catch (ModelNotFoundException $e) {
            Log::channel('contrats')->error('GetByNum : '.$e->getMessage());

            return response()->json(['message' => 'Contrat introuvable'], 500);
        } catch (Exception $e) {
            Log::channel('contrats')->error('GetByNum : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            if (isset($request->all()['id'])) {
                $this->authorize('update', Contrat::findOrFail($request->all()['id']));
            } else {
                $this->authorize('create', Contrat::class);
            }
            $data = $this->ContartService->store($request);

            return response()->json($data);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (ErrorException $e) {
            Log::channel('contrats')->error('Store : '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        } catch (ModelNotFoundException $e) {
            Log::channel('contrats')->error('Store : '.$e->getMessage());

            return response()->json(['message' => 'Contrat introuvable'], 500);
        } catch (Exception $e) {
            Log::channel('contrats')->error('Store : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $this->authorize('update', Contrat::findOrFail($id));

            $data = $this->ContartService->update($request, $id);

            return response()->json($data);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (ModelNotFoundException $e) {
            Log::channel('contrats')->error('Update : '.$e->getMessage());

            return response()->json(['message' => 'Contrat introuvable'], 500);
        } catch (Exception $e) {
            Log::channel('contrats')->error('Update : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function storeDocuments(Request $request): JsonResponse
    {
        try {
            $this->authorize('upload_documents', Contrat::class);

            $documents = $this->documentService->storeRibDocuments($request);

            return response()->json($documents, 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Cette action n\'est pas autorisée'], 401);
        } catch (Exception $e) {
            Log::channel('contrats')->error('Store Rib Document : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function getDocuments(string $id): JsonResponse
    {
        try {
            $documents = $this->ContartService->getDocuments($id);

            return response()->json($documents, 200);
        } catch (Exception $e) {
            Log::channel('contrats')->error('Get Documents : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
