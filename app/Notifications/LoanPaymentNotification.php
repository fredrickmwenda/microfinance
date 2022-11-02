<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanPaymentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

     //pass the loan object to the constructor
    public function __construct($dataToSend)
    {
        $this->data = $dataToSend;
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
                    ->line('Loan Payment Notification')
                    ->line($this->data['name'] . ' has made a payment of ' . $this->data['amount'] . ' on loan ' . $this->data['loan_id'] . 'at ' . $this->data['transaction_date'] .'The transaction reference is ' . $this->data['transaction_reference'])
                    ->line('The remaining balance is ' . $this->data['balance'])
                    ->action('View Loan', url('/'))
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
            'customer_name' => $this->data['name'],
            'amount' => $this->data['amount'],
            'loan_id' => $this->data['loan_id'],
            'transaction_date' => $this->data['transaction_date'],
            'transaction_reference' => $this->data['transaction_reference'],
            'balance' => $this->data['balance'],
        ];
    }
}
