<?php

namespace App\Helpers;

class LoanHelper{
//check that phone numbers are in kenyan format of 07 or 011
public static function  checkPhoneNumber($phone){
    // $phone ='0111870000';
    if(preg_match('/^07[0-9]{8}$/', $phone) || preg_match('/^011[0-9]{7}$/', $phone)){
        return true;
    }else{
        return false;
    }
}

    
 
}


