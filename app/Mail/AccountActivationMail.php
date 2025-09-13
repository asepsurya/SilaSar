<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountActivationMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        return $this->view('auth.emails.activation')
                    ->with([
                        'user' => $this->user,
                        'token' => $this->token,
                    ])
                    ->subject('Account Activation');
    }
}
