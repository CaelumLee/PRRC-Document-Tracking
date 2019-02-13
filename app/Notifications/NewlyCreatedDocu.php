<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewlyCreatedDocu extends Notification implements ShouldQueue
{
    use Queueable;

    protected $docu_data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($docu_data)
    {
        $this->docu_data = $docu_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'data' => [
                'docu_id' => $this->docu_data['docu'],
                'reference_number' => $this->docu_data['ref_num'],
            ]
        ];
    }
    
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'data' => [
                'docu_id' => $this->docu_data['docu'],
                'reference_number' => $this->docu_data['ref_num'],
            ]
        ]);
    }

}
