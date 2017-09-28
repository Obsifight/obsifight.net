<?php

use Illuminate\Database\Seeder;

class VotesTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      if (DB::getConfig()['driver'] === 'mysql')
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      DB::table('vote_kits')->truncate();
      if (DB::getConfig()['driver'] === 'mysql')
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

      DB::table('vote_kits')->insert([
        'name' => 'Voter1',
        'content' => 'Stuff P4 Titanium, Épée Sharpness 5 Fire 2 Unbreaking 3 en Titanium, Arc en Titanium Power 5 Punch 1 Flame 1 Unbreaking 3 Infinity 1, 1 Flèche, 48 TNT, 1 XénoTNT, 3 Orbe de réparation, 32 enderpearls, 10 Stacks de bouteille d\'enchantement, 12 cores neutres, 4 lingots de xénotium, 3 cookies d\'obsidian, 2 cookies de titanium, 1 cookie d\'améthyste, 1 cookie de garnet',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter2',
        'content' => 'Stuff P4 Titanium, Épée Sharpness 4 Fire 2 en Titanium, Arc en Titanium Power 4 Flame 1 Unbreaking 3, 64 Flèches, 32 TNT, 2 Orbe de réparation, 24 enderpearls, 8 Stacks de bouteille d\'enchantement, 11 cores neutres, 3 lingots de xénotium, 2 cookies d\'obsidian, 2 cookies de titanium, 1 cookie d\'améthyste, 1 cookie de garnet',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter3',
        'content' => 'Stuff P3 Titanium, Épée Sharpness 3 Fire 1 en Titanium, Arc en Titanium Power 3 Unbreaking 3, 64 Flèches, 32 TNT, 1 Orbe de réparation, 16 enderpearls, 6 Stacks de bouteille d\'enchantement, 10 cores neutres, 2 lingots de xénotium, 1 cookie d\'obsidian, 2 cookies de titanium, 2 cookies d\'améthyste, 1 cookie de garnet',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter4',
        'content' => 'Stuff P4 Améthyste, Épée Sharpness 5 Fire 2 en Améthyste, Arc en Améthyste Power 5 Flame 1 Unbreaking 3 Infinity 1, 1 Flèche, 16 TNT, 1 Orbe de réparation, 12 enderpearls, 4 Stacks de bouteille d\'enchantement, 1 lingot de xénotium, 9 cores neutres, 1 cookie d\'obsidian, 1 cookie de titanium, 2 cookies d\'améthyste, 2 cookies de garnet',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter5',
        'content' => 'Stuff P4 Garnet, Épée Sharpness 5 Fire 2 en Garnet, Arc en Garnet Power 5 Unbreaking 3 Infinity 1, 1 Flèche, 12 TNT, 8 enderpearls, 2 Stacks de bouteille d\'enchantement, 8 cores neutres, 10 lingots d\'obsidian, 1 cookie de titanium, 2 cookies d\'améthyste, 2 cookies de garnet',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter6',
        'content' => 'Stuff P4 Manganese, Épée Sharpness 5 Fire 2 en Manganese, Arc en Manganese Power 5 Flame 1 Unbreaking 3, 64 Flèches, 8 TNT, 6 enderpearls, 1 Stacks de bouteille d\'enchantement, 8 cores neutres, 5 lingots d\'obsidian, 2 cookies d\'améthyste, 3 cookies de garnet',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter7',
        'content' => '20 lingots d\'obsidian, 7 cores neutres',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter8',
        'content' => '16 lingots d\'obsidian, 6 cores neutres',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter9',
        'content' => '12 lingots d\'obsidian, 5 cores neutres',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter10',
        'content' => '8 lingots d\'obsidian, 4 cores neutres',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter11',
        'content' => '20 lingots de titanium, 3 cores neutres',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter12',
        'content' => '16 lingots de titanium, 2 cores neutres',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter13',
        'content' => '12 lingots de titanium, 2 cores neutres',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter14',
        'content' => '32 lingots d\'améthyste, 2 cores neutres',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_kits')->insert([
        'name' => 'Voter15',
        'content' => '32 lingots de garnet, 2 cores neutres',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

    }
}
