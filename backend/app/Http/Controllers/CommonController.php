<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'Produits' => '/api/common/produits',
            'Gammes' => '/api/common/gammes',
            'Fournisseurs' => '/api/common/fournisseurs',
            'roles' => '/api/common/roles',
            'permissions' => '/api/common/permissions',
        ], 200);
    }

    public function getRoles(): JsonResponse
    {
        try {
            $roles = Cache::remember('roles', 604800, function () {
                return DB::table('roles')->select('id', 'name', 'display_name')->where('name', '<>', 'ADMINISTRATEUR')->get();
            });

            return response()->json($roles);
        } catch (Exception $e) {
            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function getPermissions(): JsonResponse
    {
        try {
            $permissions = Cache::remember('permissions', 604800, function () {
                return DB::table('permissions')->select('id', 'name', 'display_name')->get();
            });

            return response()->json($permissions);
        } catch (Exception $e) {
            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }

    public function getPermissionsByRole($id): JsonResponse
    {
        try {
            return response()->json(
                DB::table('permission_role')
                    ->where('role_id', $id)
                    ->pluck('permission_id')
            );
        } catch (Exception $e) {
            return response()->json(['message' => 'Une erreur s\'est produite lors de l\'operation'], 500);
        }
    }
}
