<?php

namespace App\Listeners;

use App\Models\Loan;
use App\Notifications\LoanExpiryNotification;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoanExpiryStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
       //listen to the loan expiry event and update the status to  expired loan in the database
       $loan = Loan::find($event->loan->id);
       ///if the loan end date is less than today's date, update the status to expired
        if($loan->end_date < now()){
            $loan->status = 'overdue';
            $loan->save();
        }

        $admin = User::where('role_id', 1)->first();
        Notification::send($admin, new \App\Notifications\LoanExpiryNotification($loan));
    }
}
