<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $item->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back();
    }
}
