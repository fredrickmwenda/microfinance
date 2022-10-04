<?php

namespace App\Helpers;

// use log
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class DatesValidator
{

    /**
     * Get day from date
     * @param Carbon $date
     */

    public static function validate($from, $to)
    {
        # code...
        $to = Carbon::create($to);
        $from = Carbon::create($from);
        $now = Carbon::now();
        // Log::info($to, $from, $now);
            // dd($to, $from, $now);

        //if from is in the future
        if ($from->greaterThan($now)) {
            return 'From date must be less than now';
        }

         //if from is greater than to date
         if ($from->greaterThan($to)) {
            return 'From date must be less than to date';
        }

        return "success";
    }

    // public static function validateCampaign($from, $to)
    // {
    //     # code...
    //     $to = Carbon::create($to);
    //     $from = Carbon::create($from);
    //     $now = Carbon::now();

    //     //if from is in the future
    //     if ($to->lessThan($now)) {
    //         return 'End date must be in the future.';
    //     }

    //      //if from is greater than to date
    //      if ($from->greaterThan($to)) {
    //         return 'Start date must be less than end date.';
    //     }

    //     return "success";
    // }

}
