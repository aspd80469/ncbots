<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Notifications\Notification;

class NotificationTelegram extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ["telegram"];
    }

    public function toTelegram($notifiable)
    {
        $url = 'www.google.com';

        return TelegramMessage::create()
            ->to('378160546')
            ->content("Hi Jimmy !!!");
            // ->button('View Invoice', $url)
            // ->button('Download Invoice', $url);
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
