<?php

namespace App\Mail;

use App\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $link)
    {
      $this->user = $user;
      $this->url = $link;

      $this->subject(__('user.password.forgot.email.subject'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user.password');
    }
}
