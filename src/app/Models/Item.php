<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    use HasFactory;

    public const STATUS_ON_SALE = 1;
    public const STATUS_IN_PROGRESS = 2;
    public const STATUS_SOLD = 3;

    public const CONDITION_GOOD = 1;
    public const CONDITION_NO_NOTICEABLE_DAMAGE = 2;
    public const CONDITION_SCRATCHED = 3;
    public const CONDITION_BAD = 4;

    public function isSoldForDisplay(): bool
    {
        return in_array($this->status, [
            self::STATUS_IN_PROGRESS,
            self::STATUS_SOLD,
        ], true);
    }

    public function getImageUrlAttribute()
    {
        $path = $this->image_path;
        if (file_exists(public_path('images/' . $path))) {
            return asset('images/' . $path);
        }
        return Storage::url($path);
    }

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
            'category_id'
        )->withTimestamps();
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
