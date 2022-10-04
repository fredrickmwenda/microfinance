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
        return $this->belongsTo(Loan::class);
    }

    //money is disbursed by an admin user
    public function disburser()
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

}
