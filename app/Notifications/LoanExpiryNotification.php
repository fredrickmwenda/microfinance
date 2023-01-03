<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanExpiryNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $loan;
    public function __construct($loan)
    {
        $this->loan = $loan;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('Loan Overdue Notification')
                    ->line('Customer ' . $this->loan['first_name'] . ' ' . $this->loan['last_name'] . ' has an overdue loan of ' . $this->loan['amount'] . ' on loan ' . $this->loan['loan_id'] )
                    ->line('The loan was due on ' . $this->loan['end_date'])
                    ->action('View Loan', url('/loans/' . $this->loan['loan_id']))
                    ->line('Well received!');
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
            'loan' => $this->loan

        ];
    }
}
