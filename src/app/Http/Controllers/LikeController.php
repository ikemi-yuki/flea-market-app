<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $liked = Like::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->exists();

        if ($liked) {
            Like::where('user_id', $user->id)
                ->where('item_id', $item->id)
                ->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }
        return back();
    }
}
