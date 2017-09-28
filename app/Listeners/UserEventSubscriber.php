<?php

namespace App\Listeners;

use Illuminate\Http\Request;

class UserEventSubscriber
{
    public function __construct(Request $request)
    {
      $this->request = $request;
    }

    /**
     * Handle user login events.
     */
    public function onUserLogin($event) {
      // Log into xenforo
      if (env('APP_FORUM_ENABLED', false))
        \XF::loginAsUser($event->user->id, $event->remember);

      // Log this connection
      $log = new \App\UsersConnectionLog();
      $log->user_id = $event->user->id;
      $log->ip = $this->request->ip();
      $log->save();
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event) {}

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@onUserLogout'
        );
    }

}
