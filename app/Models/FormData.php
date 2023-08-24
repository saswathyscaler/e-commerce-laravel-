<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobileNumber',
        'alternateNumber',
        'address',
        'user_id', // Adding user_id field to associate with the user
        'order_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
