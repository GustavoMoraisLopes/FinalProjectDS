<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = route('password.reset', ['token' => $this->token, 'email' => $notifiable->email]);

        return (new MailMessage)
            ->subject('LabStock — Recuperação da Palavra‑passe')
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'LabStock'))
            ->view('emails.forgot_password', [
                'name' => $notifiable->name ?? 'Utilizador',
                'resetUrl' => $resetUrl,
                'appName' => config('app.name', 'LabStock'),
                'supportEmail' => 'no-reply@labstock.pt'
            ]);
    }
}
