<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');
        $keyword = $request->query('keyword');

        $query = Item::query();

        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $query->whereHas('likes', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $items = $query
            ->keywordSearch($keyword)
            ->get();

        return view('index', compact('items', 'tab', 'keyword'));
    }

    public function detail($item_id)
    {
        $item = Item::with([
            'categories',
            'likes',
            'comments.user.profile',
        ])->findOrFail($item_id);

        $conditionText = [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い'
        ];

        $likeCount = $item->likes->count();
        $commentCount = $item->comments->count();

        $isLiked = auth()->check()
                ? $item->isLikedBy(auth()->user())
                :false;

        return view('item', compact('item','conditionText', 'likeCount', 'commentCount', 'isLiked'));
    }
}
