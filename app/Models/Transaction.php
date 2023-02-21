<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function payment_gateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    //belongs to a loan
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('id', 'like', '%' . $search . '%')
            ->orWhere('amount', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%')
            ->orWhere('payment_method', 'like', '%' . $search . '%')
            ->orWhere('payment_gateway_id', 'like', '%' . $search . '%')
            ->orWhere('user_id', 'like', '%' . $search . '%')
            ->orWhere('customer_id', 'like', '%' . $search . '%')
            ->orWhere('invoice_id', 'like', '%' . $search . '%')
            ->orWhere('payment_id', 'like', '%' . $search . '%')
            ->orWhere('created_at', 'like', '%' . $search . '%')
            ->orWhere('updated_at', 'like', '%' . $search . '%');
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
