<?php

namespace App\Jobs;

use App\Mail\LoanApproved;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail as FacadesMail;


class SendLoanApproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    //setup details
    public  $details;
    public $mail;
    public function __construct( $details, $mail)
    {
        $this->details = $details;
        $this->mail = $mail;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mail = new LoanApproved($this->details);
        FacadesMail::to($this->mail)->send($mail);      
    }
}
