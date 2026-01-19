<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
        return $this->belongsToMany(
            Category::class,
            'item_category',
            'item_id',
            'category_id')
                    ->withTimestamps();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy($user)
    {
        if (!$user) {
            return false;
        }
        return $this->likes()
                    ->where('user_id', $user->id)
                    ->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
        return $query;
    }
}
