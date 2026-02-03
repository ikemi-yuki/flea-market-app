<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 購入時の配送先住所を保持するモデル
class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'shipping_post_code',
        'shipping_address',
        'shipping_building'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
