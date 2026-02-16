<?php

namespace App\Http\Controllers;

use App\Models\Item;

class LikeController extends Controller
{
    public function store($item_id)
    {
        $item = Item::findOrFail($item_id);
        $userId = auth()->id();

        $like = $item->likes()
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            $like->delete();

        } else {
            $item->likes()->create([
                'user_id' => $userId,
            ]);
        }
        return back();
    }
}
