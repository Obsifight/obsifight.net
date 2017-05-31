<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ObsifightServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      /*
        == API ==
      */
        if (!class_exists('ApiObsifight'))
          require base_path('vendor/Eywek/API/ApiObsifight.class.php');
        $api = new \ApiObsifight(env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS'));
        $this->app->instance('\ApiObsifight', $api);

      /*
        == SERVER ==
      */
      require base_path('vendor/Eywek/Server/MinewebServer.class.php');
      require base_path('vendor/Eywek/Server/Exceptions.php');
      $server = new \Methods(env('MINECRAFT_SERVER_SECRET_KEY'), env('MINECRAFT_SERVER_IP'), env('MINECRAFT_SERVER_PORT'));
      $this->app->instance('\Server', $server);
    }
}
