<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($notifiable->email_verified) {

            return (new MailMessage)
                ->from(setting('contact_help_desk_email'), config('app.name'))
                ->subject('Welcome to ' . config('app.name'))
                ->greeting('Welcome ' . ucwords($notifiable->name))
                ->line('Thank you for joining ' . config('app.name'))
                ->action('Go to dashboard', route('admin.dashboard'));

        }

        $confirmationLink = $this->getEmailVerificationLink($notifiable);

        return (new MailMessage)
            ->from(setting('contact_help_desk_email'), config('app.name'))
            ->subject('Confirm Email | ' . config('app.name'))
            ->greeting('Hello ' . ucwords($notifiable->name))
            ->action('Confirm Email', $confirmationLink)
            ->line('Please Confirm your email, by clicking above button');

    }


    protected function getEmailVerificationLink($user)
    {
        $data = [
            'email' => $user->email,
            'token' => $user->email_verification_token
        ];

        $data = encrypt(base64_encode(json_encode($data)));

        return route('email.confirmation.confirm', $data);
    }
}
