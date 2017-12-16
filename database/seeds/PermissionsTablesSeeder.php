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
        if (DB::getConfig()['driver'] === 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        if (DB::getConfig()['driver'] === 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'user',
            'display_name' => 'Joueur',
            'description' => 'Default user',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'restricted',
            'display_name' => 'Compte restreint',
            'description' => 'Le joueur pert certaines permissions',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'moderator',
            'display_name' => 'Modérateur',
            'description' => 'Accès a des fonctionnalités de modération',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('roles')->insert([
            'id' => 4,
            'name' => 'admin',
            'display_name' => 'Administrateur',
            'description' => 'Accès restreint au panel admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('roles')->insert([
            'id' => 5,
            'name' => 'creator',
            'display_name' => 'Fondateur',
            'description' => 'Accès complet au panel admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // add permissions
        if (DB::getConfig()['driver'] === 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        if (DB::getConfig()['driver'] === 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('permissions')->insert([
            'id' => 1,
            'name' => 'user-send-confirmation-email',
            'display_name' => 'Renvoie de l\'email de confirmation',
            'description' => 'Renvoyer l\'email de confirmation',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 2,
            'name' => 'user-edit-password',
            'display_name' => 'Éditer le mot de passe',
            'description' => 'Éditer son mot de passe depuis le profil',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 3,
            'name' => 'user-request-edit-email',
            'display_name' => 'Demande de changement d\'email',
            'description' => 'Demander un changement d\'email depuis le profil',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 4,
            'name' => 'user-edit-username',
            'display_name' => 'Éditer le pseudo',
            'description' => 'Éditer son pseudo depuis le profil',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 5,
            'name' => 'user-transfer-money',
            'display_name' => 'Transférer des points boutique',
            'description' => 'Transférer des points boutique à un autre joueur',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 6,
            'name' => 'user-upload-skin',
            'display_name' => 'Changer son skin',
            'description' => 'Changer son skin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 7,
            'name' => 'user-upload-cape',
            'display_name' => 'Changer sa cape',
            'description' => 'Changer sa cape',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 8,
            'name' => 'user-enable-two-factor-auth',
            'display_name' => 'Activer la double authentification',
            'description' => 'Activer la double authentification',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 9,
            'name' => 'user-disable-two-factor-auth',
            'display_name' => 'Désactiver la double authentification',
            'description' => 'Désactiver la double authentification',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 10,
            'name' => 'user-enable-obsiguard',
            'display_name' => 'Activer ObsiGuard',
            'description' => 'Activer ObsiGuard',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 11,
            'name' => 'user-disable-obsiguard',
            'display_name' => 'Désactiver ObsiGuard',
            'description' => 'Désactiver ObsiGuard',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 12,
            'name' => 'user-add-ip-obsiguard',
            'display_name' => 'Ajouter une IP sur ObsiGuard',
            'description' => 'Ajouter une IP sur ObsiGuard',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 13,
            'name' => 'user-remove-ip-obsiguard',
            'display_name' => 'Supprimer une IP sur ObsiGuard',
            'description' => 'Supprimer une IP sur ObsiGuard',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 14,
            'name' => 'user-enable-dynamic-ip-obsiguard',
            'display_name' => 'Activer le système d\'IP dynamique sur ObsiGuard',
            'description' => 'Activer le système d\'IP dynamique sur ObsiGuard',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 15,
            'name' => 'user-disable-dynamic-ip-obsiguard',
            'display_name' => 'Désactiver le système d\'IP dynamique sur ObsiGuard',
            'description' => 'Désactiver le système d\'IP dynamique sur ObsiGuard',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 16,
            'name' => 'user-link-google-account',
            'display_name' => 'Lier YouTube',
            'description' => 'Lier son compte Google au compte site',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 17,
            'name' => 'user-youtube-view-own-videos',
            'display_name' => 'Voir ses vidéos',
            'description' => 'Voir ses vidéos YouTube',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 18,
            'name' => 'user-youtube-get-remuneration',
            'display_name' => 'Rémunérer ses vidéos',
            'description' => 'Récupérer la rémunération de ses vidéos YouTube',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 19,
            'name' => 'user-link-twitter-account',
            'display_name' => 'Lier son compte Twitter',
            'description' => 'Lier son compte Twitter',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 20,
            'name' => 'wiki-see-not-displayed-article',
            'display_name' => 'Voir un article caché sur le wiki',
            'description' => 'Voir un article caché sur le wiki',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 21,
            'name' => 'shop-buy',
            'display_name' => 'Acheter',
            'description' => 'Acheter un article sur la boutique',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 22,
            'name' => 'sanction-contest',
            'display_name' => 'Contester',
            'description' => 'Contester une sanction',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 23,
            'name' => 'sanction-contest-close',
            'display_name' => 'Fermer une contestation',
            'description' => 'Fermer une contestation de sanction',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 24,
            'name' => 'sanction-contest-edit',
            'display_name' => 'Editer une contestation',
            'description' => 'Débannir ou réduire une sanction',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 25,
            'name' => 'sanction-contest-comment',
            'display_name' => 'Commenter une contestation',
            'description' => 'Commenter une sanction',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 26,
            'name' => 'shop-credit-add',
            'display_name' => 'Ajouter des créditer',
            'description' => 'Pouvoir créditer son compte en points',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('permissions')->insert([
            'id' => 27,
            'name' => 'view-admin-dashboard',
            'display_name' => 'Accès au panel admin',
            'description' => 'Pouvoir voir le dashboard admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 28,
            'name' => 'view-admin-stats-shop',
            'display_name' => 'Accès au stats de la boutique',
            'description' => 'Accès au stats de la boutique sur le panel admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('permissions')->insert([
            'id' => 29,
            'name' => 'shop-admin-vouchers',
            'display_name' => 'Modifier les bons',
            'description' => 'Modifier sur le panel admin les bons de points',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // link permissiosn

        if (DB::getConfig()['driver'] === 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permission_role')->truncate();
        if (DB::getConfig()['driver'] === 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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
            'permission_id' => 6,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 7,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 8,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 9,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 10,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 11,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 12,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 13,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 14,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 15,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 16,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 17,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 18,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 19,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 21,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 22,
            'role_id' => 1
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 26,
            'role_id' => 1
        ]);

        DB::table('permission_role')->insert([
          'permission_id' => 23,
          'role_id' => 3
        ]);
        DB::table('permission_role')->insert([
          'permission_id' => 24,
          'role_id' => 3
        ]);
        DB::table('permission_role')->insert([
          'permission_id' => 25,
          'role_id' => 3
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
        DB::table('permission_role')->insert([
            'permission_id' => 8,
            'role_id' => 2
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 9,
            'role_id' => 2
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 10,
            'role_id' => 2
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 11,
            'role_id' => 2
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 12,
            'role_id' => 2
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 13,
            'role_id' => 2
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 14,
            'role_id' => 2
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 15,
            'role_id' => 2
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => 27,
            'role_id' => 4
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 28,
            'role_id' => 5
        ]);
        DB::table('permission_role')->insert([
            'permission_id' => 29,
            'role_id' => 5
        ]);
    }
}
