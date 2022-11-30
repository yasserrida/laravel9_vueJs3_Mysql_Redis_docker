<?php

namespace App\Http\Controllers;

use App\Service\NotificationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected NotificationService $NotificationService;

    public function __construct(NotificationService $NotificationService)
    {
        $this->NotificationService = $NotificationService;
    }

    public function getAll(): JsonResponse
    {
        try {
            return response()->json($this->NotificationService->getAll(), 200);
        } catch (Exception $e) {
            Log::error('Index : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function getUnreaded(): JsonResponse
    {
        try {
            return response()->json($this->NotificationService->getUnreaded(), 200);
        } catch (Exception $e) {
            Log::error('Store : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function markAsRead(): JsonResponse
    {
        try {
            $this->NotificationService->markAsRead();

            return response()->json('success', 200);
        } catch (Exception $e) {
            Log::error('Show : '.$e->getMessage());

            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
