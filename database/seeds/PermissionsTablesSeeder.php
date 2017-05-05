<?php

use Illuminate\Database\Seeder;

class PermissionsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Add default role
      DB::table('roles')->truncate();
      DB::table('roles')->insert([
        'name' => 'user',
        'display_name' => 'Joueur',
        'description' => 'Default user',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('roles')->insert([
        'name' => 'restricted',
        'display_name' => 'Compte restreint',
        'description' => 'Le joueur pert certaines permissions',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      // add permissions
      DB::table('permissions')->truncate();
      DB::table('permissions')->insert([
        'name' => 'user-send-confirmation-email',
        'display_name' => 'Renvoie de l\'email de confirmation',
        'description' => 'Renvoyer l\'email de confirmation',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('permissions')->insert([
        'name' => 'user-edit-password',
        'display_name' => 'Éditer le mot de passe',
        'description' => 'Éditer son mot de passe depuis le profil',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('permissions')->insert([
        'name' => 'user-request-edit-email',
        'display_name' => 'Demande de changement d\'email',
        'description' => 'Demander un changement d\'email depuis le profil',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('permissions')->insert([
        'name' => 'user-edit-username',
        'display_name' => 'Éditer le pseudo',
        'description' => 'Éditer son pseudo depuis le profil',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('permissions')->insert([
        'name' => 'user-transfer-money',
        'display_name' => 'Transférer des points boutique',
        'description' => 'Transférer des points boutique à un autre joueur',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      // link permissiosn
      DB::table('permission_role')->truncate();
      DB::table('permission_role')->insert([
        'permission_id' => 1,
        'role_id' => 1
      ]);
      DB::table('permission_role')->insert([
        'permission_id' => 2,
        'role_id' => 1
      ]);
      DB::table('permission_role')->insert([
        'permission_id' => 3,
        'role_id' => 1
      ]);
      DB::table('permission_role')->insert([
        'permission_id' => 4,
        'role_id' => 1
      ]);
      DB::table('permission_role')->insert([
        'permission_id' => 5,
        'role_id' => 1
      ]);
      DB::table('permission_role')->insert([
        'permission_id' => 1,
        'role_id' => 2
      ]);
      DB::table('permission_role')->insert([
        'permission_id' => 2,
        'role_id' => 2
      ]);
      DB::table('permission_role')->insert([
        'permission_id' => 3,
        'role_id' => 2
      ]);
    }
}
