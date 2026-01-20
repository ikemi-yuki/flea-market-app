<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        $user = Auth::user();

        $profile = $user->profile;

        $address = session('purchase_address');

        if (!$address) {
            $address = [
                'post_code' => $profile->post_code,
                'address' => $profile->address,
                'building' => $profile->building,
            ];
        }

        $paymentMethod = session('payment_method', 1);

        return view('purchase', compact('item', 'address', 'paymentMethod'));
    }
}
