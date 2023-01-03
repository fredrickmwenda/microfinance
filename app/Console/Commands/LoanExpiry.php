<?php

namespace App\Console\Commands;

use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LoanExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue loans';


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $loans = Loan::where('end_date', '<', Carbon::now())->get();

        foreach ($loans as $loan) {
            # code...
            $loan->status = "overdue";
            $loan->save();
        }
    }
}
