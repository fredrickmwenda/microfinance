<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;
    protected $guarded = [];

    //belongs to abranch
    public function branch()
    {
        return $this->belongsTo('App\Models\branch', 'branch_id', 'id');
    }

//customer is created by a user
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    //a customer has many loans
    public function loans()
    {
        return $this->hasMany('App\Models\Loan', 'customer_id', 'id');
    }

    //a customer has many payments
    public function payments()
    {
        return $this->hasMany('App\Models\LoanPayment', 'customer_id', 'id');
    }

    //a customer receives many disbursements
    public function disbursements()
    {
        return $this->hasMany('App\Models\Disburse', 'disbursed_to', 'id');
    }


}
