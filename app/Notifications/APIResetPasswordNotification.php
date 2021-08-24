<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class APIResetPasswordNotification extends Notification
{
    use Queueable;

    protected $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->greeting('Bonjour')
            ->line('La réinitialisation du mot de passe associé à votre compte "CClasse" a été demandée.')
            ->line('Pour donner suite, merci de copier/coller le code ci-dessous dans le champ prévu à cet effet dans l\'application.')
            ->line($this->token)
            ->line('Ce code expirera au bout d\'une heure.')
            ->line('Si vous n\'êtes pas à l\'origine de cette réinitialisation, nous vous prions d\'ignorer ce message et d\'accepter nos excuses pour ce désagrément.')
            ->subject('CClasse - réinitialisation de votre mot de passe');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
