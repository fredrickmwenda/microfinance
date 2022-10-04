<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class branch extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    //has many customers
    public function customers()
    {
        return $this->hasMany('App\Models\Customer');
    }
}
