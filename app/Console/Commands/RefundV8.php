<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefundV8 extends Command
{
    private $items = array(
        21, // Kit minerai
        22, // Kit alchimiste
        23, // Kit construction
        24, // Kit destruction
        25, // Kit druide
        26, // Kit explorateur
        27, // Kit golden
        28, // Kit enchantement
        47, // Annonce légendaire à votre connexion
    );

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund:v8';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refund users for v8 version';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $save = false;
        if ($this->confirm('Do you want to save refunds?'))
            $save = true;

        $itemsPrice = array();

        $global_refund = 0;
        $refund_others_versions = 0;
        $users_refunded = 0;
        $users_refund_others_versions = 0;
        $time = microtime(true);

        $this->info('Starting...');

        $this->info('Connect to v7 database...');
        $db_v7 = DB::connection('web_v7');

        // Find items price
        $this->info('Find prices for items...');
        foreach ($this->items as $item_id) {
            $findItemPrice = $db_v7->selectOne('SELECT price FROM shop__items WHERE id = ?', array($item_id));
            if (!empty($findItemPrice))
                $itemsPrice[$item_id] = $findItemPrice->price;
        }

        // All users
        $this->info('Find users...');
        $users = \App\User::get(); // On prends tous les utilisateurs
        $count = count($users);

        $i = 0;
        foreach ($users as $k => $v) { // On les parcours
            $i++;

            $user_pseudo = $v->username;
            $user_money = $v->money;
            $user_added_money = 0;
            $user_id = $v->id;

            $this->info('- [' . $i . '/' . $count . '] Player : ' . $user_pseudo);

            /*
              === Check si le joueur s'est connecté en V7 ===
            */
            if(\App\UsersVersion::where('user_id', $user_id)->where('version', 7)->count() == 0) {
                $this->comment('    Non connecté lors de la V7.');
                continue;
            }

            /*
              === Historique d'achats parmis $items lors de la V7 ===
            */
            $findBuysOnV7 = $db_v7->select('SELECT item_id FROM shop__items_buy_histories WHERE user_id = ? AND (item_id = '.implode(' OR item_id = ', $this->items).')', array($user_id));
            if (!empty($findBuysOnV7)) {
                $added_money = 0;
                foreach ($findBuysOnV7 as $buy) {
                    $item_id = $buy->item_id;
                    if (isset($itemsPrice[$item_id])) {
                        $added_money += floatval($itemsPrice[$item_id]);
                        $user_added_money += floatval($itemsPrice[$item_id]);
                    } else {
                        $this->comment('    Erreur : Aucun article trouvé lors de la V7 (ID: '.$item_id.')');
                    }
                }
                $this->comment('    Articles achetés lors de la V7 : '.$added_money.' PB');
            } else {
                $this->comment('    Aucun achats trouvés lors de la V7');
            }
            /*
              === Historique & ajouts de points & notifications ===
            */

            if($user_added_money > 0) {

                $users_refunded++;

                $this->comment('    Remboursé au total de : '.$user_added_money);

                $user_new_sold = $user_money + $user_added_money;

                $global_refund += $user_added_money;

                $user = \App\User::find($user_id);
                $user->money = $user_new_sold;

                $history = new \App\UsersRefundHistory();
                $history->user_id = $user->id;
                $history->amount = $user_added_money;

                $notification = new \App\Notification();
                $notification->user_id = $user->id;
                $notification->type = 'success';
                $notification->key = 'user.refund.msg';
                $notification->vars = ['money' => $user_added_money];
                $notification->auto_seen = 0;

                if ($save) {
                    $user->save();
                    $history->save();
                    $notification->save();
                }


            } else {
                $this->comment('    Non remboursé.');
            }

            unset($user_pseudo);
            unset($user_money);
            unset($user_id);
        }

        $this->info("\n\n");
        $this->info('Time: '.(microtime(true)-$time).' sec.');
        $this->comment('Total remboursé des précédentes versions (V2, V3, V4, V5, V6) : '.$refund_others_versions.' PB');
        $this->comment('Total utilisateurs remboursés des précédentes versions : '.$users_refund_others_versions);
        $this->comment('Total remboursé de cette version (V7) : '.($global_refund - $refund_others_versions).' PB');
        $this->comment('Total utilisateurs remboursés de cette version (V7) : '.($users_refunded - $users_refund_others_versions));
        $this->info('Total utilisateurs remboursés : '.$users_refunded);
        $this->info('Total remboursé : '.$global_refund.' PB');

    }
}
