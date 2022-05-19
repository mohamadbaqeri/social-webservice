<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class AdminPostReject extends Notification
{
    use Queueable;

    private $userId;
    private $postTitle;
    private $disproof;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userId, $postTitle, $disproof)
    {
        $this->userId = $userId;
        $this->postTitle = $postTitle;
        $this->disproof = $disproof;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('your post rejected')
            ->line(url('/your post with user-id' . ' ' . $this->userId . ' ' . 'and title' . ' ' . $this->postTitle . ' ' . 'rejected, because ' . $this->disproof));
    }

    public function toNotification()
    {
        return (new Notification);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
