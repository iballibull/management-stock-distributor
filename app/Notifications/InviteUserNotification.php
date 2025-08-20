<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteUserNotification extends Notification
{
    use Queueable;

    protected string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/register/' . $this->token);

        return (new MailMessage)
            ->subject('Undangan Pendaftaran Akun')
            ->greeting('CV. Inti Bina Ilmu')
            ->line('Halo!')
            ->line('Anda menerima undangan untuk mendaftar sebagai pengguna sistem kami.')
            ->action('Daftar Sekarang', $url)
            ->line('Klik tombol di atas untuk memulai proses pendaftaran Anda.')
            ->salutation('Hormat kami, CV. Inti Bina Ilmu');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
