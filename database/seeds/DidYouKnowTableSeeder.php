<?php

use Illuminate\Database\Seeder;

class DidYouKnowTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      DB::table('did_you_knows')->truncate();
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');

      DB::table('did_you_knows')->insert([
        'text' =>"ObsiFight a été fondé par Suertz en 2014.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Sur la boutique, certaines solutions sont plus avantageuses que d'autres.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"La v4 d'ObsiFight était en 1.8.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"La limite d'AP (4 claims) ne porte pas sur les coins.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"La TNT au Xénotium a fait crasher le serveur, lors de son premier test.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Le KingZombie est apparu pour la première fois lors de la v4.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Les échelles de fer sont apparues pour la première fois lors de la v3.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Le Sadian s'appelait auparavant Grobsi.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"L'Orbe de réparation peut être enchantée \"Incassable III\".",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Le TP-Kill est autorisé, mais pas les demandes de tp dans le chat.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Les joueurs sont capables de réfléchir seuls.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Le serveur Obsifight possède un Twitter et un Facebook.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Au départ, il y avait 3 fondateurs: antoinewin, dem0niak et Suertzz.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"L'usebug bateau a toujours été interdit.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Le kit \"Obsidien\" s'appelait auparavant kit warrior.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Les KOTH ont fait leur apparition pour la première fois lors du début de la v4.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"dem0niak était fondateur à la v1 mais il avait fait le choix d'être aussi modérateur.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Un nouveau règlement pour les factions a été mis en place pour v5.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"La TNT AU Xénotium a fait son apparition en version 4.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Le manganèse a disparu lors de la v3 mais a fait son retour lors de la v4.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Le xénotium et l'obsidienne n'étaient pas présent au début de la v1 mais sont arrivés pendant cette version.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('did_you_knows')->insert([
        'text' =>"Le bloc d'obsidienne casse en 1 coup de tnt.",
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
    }
}
