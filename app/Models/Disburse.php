<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disburse extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    //money is disbursed by an admin user
    public function disburser()
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    //money is disbursed to a client
    public function disbursedTo()
    {
        return $this->belongsTo(Client::class, 'disbursed_to');
    }

}
