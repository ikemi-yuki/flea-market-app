<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'condition',
        'brand',
        'description',
        'price',
        'status'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category')
                    ->withTimestamps();
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes')
                    ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
