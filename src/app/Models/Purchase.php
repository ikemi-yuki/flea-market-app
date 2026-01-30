<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    public const PAYMENT_METHOD_CONVENIENCE_STORE = 1;
    public const PAYMENT_METHOD_CARD = 2;

    protected $fillable = [
        'user_id',
        'item_id',
        'address_id',
        'payment_method'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
