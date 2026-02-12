<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class SellController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        DB::beginTransaction();

        try {
            $path = $request->file('image_path')->store('items', 'public');

            $item = Item::create([
                'user_id' => Auth::id(),
                'image_path' => $path,
                'name' => $request->name,
                'condition' => $request->condition,
                'brand' => $request->brand,
                'description' => $request->description,
                'price' => $request->price,
                'status' => Item::STATUS_ON_SALE,
            ]);

            $item->categories()->sync(
                $request->input('categories', [])
            );

            DB::commit();

            return redirect()
                ->route('mypage.index');

        } catch (\Throwable $e) {
            DB::rollBack();

            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return back()
                ->withInput();
        }
    }
}
