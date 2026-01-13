<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $items = Item::whereHas('purchase', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->latest()->get();
        } else {
            $items = $user->sellingItems()->get();
        }

        return view('mypage', compact('items','user', 'page'));
    }
}
