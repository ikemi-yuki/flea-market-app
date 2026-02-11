<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    public function getIconUrlAttribute()
    {
        return Storage::url($this->icon_path);
    }

    protected $fillable = [
        'user_id',
        'name',
        'post_code',
        'address',
        'building',
        'icon_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
