<?php

namespace App\Console\Commands;

use App\ShopVoucher;
use Discord\File;
use Discord\Webhook;
use Illuminate\Console\Command;

class DiscordVoucher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:discord-voucher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a voucher to Discord channel';

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
        // Constant
        $path = __DIR__ . '/../../../storage/app/public/voucher-code.png';
        $webhook = new Webhook(env('DISCORD_WEBHOOK'));

        // First message
        $webhook->setMessage(env('DISCORD_MSG'))->send();

        // Create voucher
        for ($i = 0; $i < 3; $i++)
        {
            // Generate code
            $voucher = new ShopVoucher();
            $voucher->code = strtoupper(substr(\Uuid::generate(), 0, 8));
            $voucher->money = 100;
            $voucher->save();

            // Generate image
            file_put_contents($path, file_get_contents('http://via.placeholder.com/350x150/ffffff/000000/?text=' . $voucher->code));
            $file = new File($path, 'code.png');

            // Send code to Discord
            $webhook->setFile($file)->send();
        }
    }
}
