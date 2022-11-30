<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $rolesListe = [
            (object) ['name' => 'ADMINISTRATEUR', 'display_name' => 'Administrateur'],
            (object) ['name' => 'RESPONSABLE', 'display_name' => 'Responsable'],
            (object) ['name' => 'GESTIONNAIRE', 'display_name' => 'Gestionnaire'],
        ];

        foreach ($rolesListe as $item) {
            Role::create(['name' => $item->name, 'display_name' => $item->display_name]);
        }

        $permissionsListe = [
            [ // ADMINISTRATEUR
                ['name' => 'CONTRAT-CHECK', 'display_name' => 'Consulter Contrat'],
                ['name' => 'CONTRAT-CREATE', 'display_name' => 'Créer Contrat'],
                ['name' => 'CONTRAT-UPDATE', 'display_name' => 'Mettre à jour Contrat'],
                ['name' => 'CONTRAT-UPLOAD', 'display_name' => 'Insérer Documents Contrat'],

                ['name' => 'NOTIFICATION-CREATE', 'display_name' => 'Créer Notification'],
                ['name' => 'NOTIFICATION-UPDATE', 'display_name' => 'Mettre à jour Notification'],
                ['name' => 'NOTIFICATION-DELETE', 'display_name' => 'Supprimer Notification'],

                ['name' => 'RECLAMATION-CREATE', 'display_name' => 'Créer Réclamation'],
                ['name' => 'RECLAMATION-UPDATE', 'display_name' => 'Mettre à jour Réclamation'],
            ],
            [ // RESPONSABLE
                ['name' => 'CONTRAT-CHECK', 'display_name' => 'Consulter Contrat'],
                ['name' => 'CONTRAT-CREATE', 'display_name' => 'Créer Contrat'],
                ['name' => 'CONTRAT-UPDATE', 'display_name' => 'Mettre à jour Contrat'],
                ['name' => 'CONTRAT-UPLOAD', 'display_name' => 'Insérer Documents Contrat'],

                ['name' => 'NOTIFICATION-CREATE', 'display_name' => 'Créer Notification'],
                ['name' => 'NOTIFICATION-UPDATE', 'display_name' => 'Mettre à jour Notification'],
                ['name' => 'NOTIFICATION-DELETE', 'display_name' => 'Supprimer Notification'],

                ['name' => 'RECLAMATION-CREATE', 'display_name' => 'Créer Réclamation'],
                ['name' => 'RECLAMATION-UPDATE', 'display_name' => 'Mettre à jour Réclamation'],
            ],
            [ // GESTIONNAIRE
                ['name' => 'RECLAMATION-CREATE', 'display_name' => 'Créer Réclamation'],
                ['name' => 'RECLAMATION-UPDATE', 'display_name' => 'Mettre à jour Réclamation'],
            ],
        ];

        $index = 0;

        foreach (Role::orderBy('id', 'ASC')->get() as $role) {
            foreach ($permissionsListe[$index] as $permission) {
                $permission = Permission::updateOrCreate(['name' => $permission['name'], 'display_name' => $permission['display_name']]);
                $role->attachPermission($permission);
            }
            $index++;
        }
    }
}
