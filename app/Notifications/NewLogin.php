<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\BroadcastNotificationCreated;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLogin extends Notification
{
    use Queueable;

    private $timeout;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($timeout)
    {

        $this->timeout = $timeout;
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
        return (new MailMessage)
                    ->line('System Usage Time')
                    ->line('after 10 Minutes, the System will logout automatically');
    }
// todo: check it
//    public function toBroadcast($notifiable)
//    {
//        return new BroadcastMessage([
//            'timeout'=>$this->timeout['timeout'],
//        ]);
//    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'timeout'=>$this->timeout['timeout']
        ];
    }
}
