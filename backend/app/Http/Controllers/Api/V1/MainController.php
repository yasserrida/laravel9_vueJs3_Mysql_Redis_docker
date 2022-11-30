<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Service\Api\V1\MainService;
use ErrorException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    protected MainService $MainService;

    public function __construct(MainService $MainService)
    {
        $this->MainService = $MainService;
    }

    public function storeContrat(Request $request): JsonResponse
    {
        try {
            return response()->json($this->MainService->storeContrat($request), 200);
        } catch (ErrorException $e) {
            Log::channel('api')->error('storeContrat : ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            Log::channel('api')->error('storeContrat : ' . $e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
