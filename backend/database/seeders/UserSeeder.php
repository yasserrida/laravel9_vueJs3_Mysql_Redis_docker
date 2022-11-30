<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@yasser.com',
            'password' => App::environment('local') ? Hash::make('123456') : Hash::make('Admin@123'),
        ]);
        $this->attacheRolePermissions($user, 'ADMINISTRATEUR');

        $user = User::create([
            'name' => 'Responsable',
            'email' => 'responsable@yasser.com',
            'password' => App::environment('local') ? Hash::make('123456') : Hash::make('Responsable@123'),
        ]);
        $this->attacheRolePermissions($user, 'RESPONSABLE');

        $user = User::create([
            'name' => 'Gestionnaire',
            'email' => 'gestionnaire@yasser.com',
            'password' => App::environment('local') ? Hash::make('123456') : Hash::make('Gestionnaire@123'),
            'owner' => 2,
        ]);
        $this->attacheRolePermissions($user, 'GESTIONNAIRE');
    }

    public function attacheRolePermissions(User $user, $roleName)
    {
        $role = Role::where('name', $roleName)->first();
        $user->attachRole($role);

        $permissions = DB::table('permission_role')->where('role_id', $role->id)->pluck('permission_id');

        foreach ($permissions as $permission) {
            $p = Permission::find($permission);
            $user->attachPermission($p);
        }
    }
}
