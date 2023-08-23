<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory; 
    
    protected $fillable = [
        'userid',
        'payment_id',
        'phn_no', 
        'amount',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}
