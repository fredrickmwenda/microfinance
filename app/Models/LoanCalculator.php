<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCalculator extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function calculateLoan($amount, $duration,  $loan_payment_type)
    {
        //loan amount should startfrom 3000 and above
        $interest = [];
        if ($amount < 3000) {
            return "Loan amount should be 3000 and above";
        }
        //from 3000 to 10000 the interest is fixed at 500
        if ($amount >= 3000 && $amount <= 10000) {
            $interest = 500;
        }{
            //going above 10000 adds by 50 for every 1000
            $interest = 500 + (($amount - 10000) / 1000) * 50;          
        }
        //duration can be weekly, monthly or yearly
        if ($duration == "weekly") {
            $duration = 52;
        } elseif ($duration == "monthly") {
            $duration = 12;
        } elseif ($duration == "yearly") {
            $duration = 1;
        } else {
            return "Duration can only be weekly, monthly or yearly";
        }
        //they are two ways for loan payment, either one time payment or installment
        if($loan_payment_type == "one_time_payment"){
            $total_amount = $amount + $interest;

            
            $data = [
                'amount' => $amount,
                'interest' => $interest,
                'total_amount' => $total_amount,
                'duration' => $duration,
                'loan_payment_type' => $loan_payment_type
            ];
            return $data;

        }
        elseif($loan_payment_type == "installment"){
            $total_amount = $amount + $interest;
            $installment = $total_amount / $duration;

           // return $installment;
            $data = [
                'amount' => $amount,
                'interest' => $interest,
                'total_amount' => $total_amount,
                'duration' => $duration,
                'installment' => $installment,
                'loan_payment_type' => $loan_payment_type
            ];
            return $data;
        }
        // else{
        //     return "Loan payment type can only be one time payment or installment";
        // }

      


        //calculate the total amount to be paid
        //$total = $amount + ($interest * $duration);



        // $duration = $duration * 12;
        // $monthly_payment = $amount * $interest * (pow((1 + $interest), $duration) / (pow((1 + $interest), $duration) - 1));
        // $total_payment = $monthly_payment * $duration;
        // $total_interest = $total_payment - $amount;
        // $data = [
        //     'monthly_payment' => $monthly_payment,
        //     'total_payment' => $total_payment,
        //     'total_interest' => $total_interest,
        // ];
        // return $data;
    }

    //oneTimeInterest
    public function oneTimeInterest()
    {
        $table_data = [];
        $table_data[] = [
            'date' => $this->first_payment_date,
            'amount' => $this->apply_amount,
            'interest' => 0,
            'penalties' => 0,
            'total' => $this->apply_amount,
        ];
        return $table_data;
    }
}
