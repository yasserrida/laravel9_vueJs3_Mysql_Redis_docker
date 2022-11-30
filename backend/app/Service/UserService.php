<?php

namespace App\Service;

use App\Exceptions\PasswrodError;
use App\Exceptions\UserAccountNotActive;
use App\Exceptions\UserDataAlreadyUsed;
use App\Events\UserDataUpdatedEvent;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use ErrorException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService
{
    public function clearCache(): void
    {
        Cache::forget('all_users');
        Cache::forget('Responsables');
        Cache::forget('Gestionnaire');
    }

    public function getAll()
    {
        return Cache::remember('all_users', 604800, function () {
            return User::select('users.id', 'users.name', 'users.email', 'role_user.role_id', 'users.active', 'users.owner', 'roles.display_name as role', 'parent.name as parent')
                ->join('role_user', 'role_user.user_id', 'users.id')
                ->join('roles', 'roles.id', 'role_user.role_id')
                ->leftjoin('users AS parent', 'users.owner', 'parent.id')
                ->where('role_user.role_id', '<>', '1')
                ->orderBy('users.id', 'ASC')
                ->get();
        });
    }

    public function store(array $data)
    {
        try {
            $data['password'] = Hash::make($data['password']);
            $data['remember_token'] = Str::random(10);

            if (!isset($data['owner']) || $data['owner'] == '') {
                $data['owner'] = Auth::guard('api')->id();
            }

            $user = User::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => $data['password'],
            ]);

            $user->attachRole(Role::where('id', $data['role_id'])->first());

            foreach ($data['permissions'] as $permission) {
                $user->attachPermission(Permission::where('id', $permission)->first());
            }

            $user->createToken('API Token');

            $this->clearCache();

            return $user;
        } catch (QueryException $e) {
            Log::channel('users')->error('Store : ' . $e->getMessage());

            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                throw new UserDataAlreadyUsed();
            }
            throw new ErrorException('Une erreur s\'est produite lors de l\'operation');
        }
    }

    public function update(array $data, string $id)
    {
        $user = User::findOrFail($id);

        try {
            if (isset($data['password']) && $data['password'] != '') {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            DB::table('role_user')->where('user_id', $id)->update([
                'role_id' => $data['role_id'],
            ]);

            DB::table('permission_user')->where('user_id', $id)->delete();

            foreach ($data['permissions'] as $permission) {
                $user->attachPermission(Permission::where('id', $permission)->first());
            }

            unset($data['role_id']);
            unset($data['permissions']);

            $user->update($data);

            $this->clearCache();

            UserDataUpdatedEvent::dispatch($id);

            return $user;
        } catch (QueryException $e) {
            Log::channel('users')->error('Update : ' . $e->getMessage());

            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                throw new UserDataAlreadyUsed();
            }
            throw new ErrorException('Une erreur s\'est produite lors de l\'operation');
        }
    }

    public function getUser()
    {
        $user = User::select('users.id', 'users.name', 'users.email', 'users.active', 'roles.name as role')
            ->join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', 'roles.id', 'role_user.role_id')
            ->where('users.id', Auth::guard('api')->id())
            ->where('active', 1)
            ->firstOrFail();

        $user['permissions'] = DB::table('permission_user')
            ->where('permission_user.user_id', Auth::guard('api')->id())
            ->join('permissions', 'permissions.id', 'permission_user.permission_id')
            ->pluck('permissions.name');

        if (!$user->active) {
            throw new UserAccountNotActive();
        }

        return $user;
    }

    public function getUserPermissions(string $id)
    {
        return DB::table('permission_user')
            ->where('user_id', $id)
            ->pluck('permission_id');
    }

    public function updateSelf(array $inputs)
    {
        $user = User::findOrFail(Auth::guard('api')->id());

        if (!Hash::check($inputs['oldPassword'], $user->password)) {
            throw new PasswrodError();
        }

        if (isset($inputs['password']) && $inputs['password'] != '') {
            $inputs['password'] = Hash::make($inputs['password']);
        } else {
            unset($inputs['password']);
        }

        $user->update($inputs);

        $this->clearCache();

        return $user;
    }

    public function getResponsables()
    {
        return Cache::remember('Responsables', 604800, function () {
            return User::select('users.name', 'users.id')
                ->join('role_user', 'role_user.user_id', 'users.id')
                ->join('roles', 'roles.id', 'role_user.role_id')
                ->where('roles.name', 'RESPONSABLE')
                ->where('active', 1)
                ->get();
        });
    }

    public function getGestionnaires()
    {
        return Cache::remember('Gestionnaires', 604800, function () {
            return User::select('users.name', 'users.id')
                ->join('role_user', 'role_user.user_id', 'users.id')
                ->join('roles', 'roles.id', 'role_user.role_id')
                ->where('roles.name', 'GESTIONNAIRE', 'GESTIONNAIRE_JUNIOR')
                ->where('active', 1)
                ->get();
        });
    }
}
