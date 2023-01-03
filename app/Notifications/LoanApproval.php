<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanApproval extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $data;

     //pass the loan object to the constructor
    public function __construct($data)
    {
        $this->data = $data;
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
                    ->line('Hello ' . $this->data['first_name'] . ' ' . $this->data['last_name'] . ',')
                    ->line('Your loan application has been approved. Please find the details below:')
                    ->line('Loan ID: ' . $this->data['loan_id'])
                    ->line('Loan Amount: ' . $this->data['amount'])
                    ->line('Loan Start Date: ' . $this->data['start_date'])
                    ->line('Loan End Date: ' . $this->data['end_date']);
                    
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
            
        ];
    }
}
