<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VoteReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vote:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset vote and give kit';

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
      // Ranking
      $kits = \App\VoteKit::get();
      $ranking = \App\Vote::where('created_at', '>=', date('Y-m-01 00:00:00', strtotime('-1 month')))->where('created_at', '<=', date('Y-m-01 00:00:00'))->groupBy('user_id')->select('user_id')->selectRaw('COUNT(*) AS votes_count')->orderBy('votes_count', 'DESC')->limit(count($kits))->get();

      // Add vote to users
      $position = 0;
      foreach ($ranking as $row) {
        $position++;

        // add notification
        $notification = new \App\Notification();
        $notification->user_id = $row->user_id;
        $notification->type = 'info';
        $notification->key = 'vote.reset.kit.get';
        $notification->vars = ['url' => url('/vote/reward/kit/get'), 'position' => $position];
        $notification->auto_seen = 0;
        $notification->save();

        // add kit
        $kit = new \App\VoteUserKit();
        $kit->user_id = $row->user_id;
        $kit->kit_id = $kits[$position-1]->id;
        $kit->save();
      }
    }
}
