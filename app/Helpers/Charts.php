<?php

namespace App\Helpers;

class Charts{
    public static function getData($arrayData): array
    {
        //check if the arrayKey values are all zero, do not return the data
        if( self::checkIfAllZero($arrayData) ){
            
            return [];
        }
        else {
        //find the last position with a non zero value in the array
        $lastNonZero = array_search(max($arrayData), $arrayData);
        
        
        //remove all key:value pairs after the last non zero value, the value pairs are in string format
        $arrayData = array_slice($arrayData, 0, array_search($lastNonZero, array_keys($arrayData)) + 1, true);

        return $arrayData;
        }

    }

    public static function checkIfAllZero($arrayData): bool
    {
        $allZero = true;
        foreach ($arrayData as $key => $value) {
            if($value != 0){
                $allZero = false;
                break;
            }
        }
        return $allZero;
    }
}