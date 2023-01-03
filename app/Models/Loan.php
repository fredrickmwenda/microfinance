<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $guarded = [];

    //a customer has many loans
    public function customer()
    {
        return $this->belongsTo(customer::class);
    }


 
     //a loan is created by one user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    //a loan is approved by one user
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function loan_calculator()
    {
        return $this->hasOne(LoanCalculator::class);
    }



    public function loan_attachments()
    {
        return $this->hasMany(LoanAttachment::class);
    }

    public function loan_payments()
    {
        return $this->hasMany(LoanPayment::class);
    }
    //a loan  has many disbursements, but only one is successful once 

    public function disburse()
    {
        return $this->hasOne(Disburse::class);
    }

    //get loans created by a specific user
    public function scopeCreatedBy($query, $user_id)
    {
        return $query->where('created_by', $user_id);
    }
    

}
