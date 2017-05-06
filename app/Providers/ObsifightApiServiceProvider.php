<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ObsifightApiServiceProvider extends ServiceProvider
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
      /*try {
        $apiResolved = $this->app->make('\ApiObsifight');
      } catch (Exception $e) {*/
        // Bind instance
        if (!class_exists('ApiObsifight'))
          require base_path('vendor/Eywek/API/ApiObsifight.class.php');
        $api = new \ApiObsifight(env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS'));
        $this->app->instance('\ApiObsifight', $api);
      //}
    }
}
