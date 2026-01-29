<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PurchaseController extends Controller
{
    public function show(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if (!session()->has('current_item_id') || session('current_item_id') != $item_id) {
            session()->forget('payment_method');
            session(['current_item_id' => $item_id]);
        }

        $user = Auth::user();

        $profile = $user->profile;

        if ($request->has('payment_method')) {
            session(['payment_method' => $request->payment_method]);
        }

        $paymentMethod = session('payment_method');

        $address = session('purchase_address');

        if (!$address) {
            $address = [
                'post_code' => $profile->post_code,
                'address' => $profile->address,
                'building' => $profile->building,
            ];
        }

        return view('purchase', compact('item', 'address', 'paymentMethod'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        if ($item->status === Item::STATUS_SOLD) {
            abort(403, 'この商品は購入済みです');
        }

        $addressData = session('purchase_address')
            ?? [
                'post_code' => $user->profile->post_code,
                'address' => $user->profile->address,
                'building' => $user->profile->building,
            ];

        DB::transaction(function () use ($user, $item, $addressData, $request) {
            $address = Address::create([
                'user_id' => $user->id,
                'shipping_post_code' => $addressData['post_code'],
                'shipping_address' => $addressData['address'],
                'shipping_building' => $addressData['building'],
            ]);

            Purchase::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'address_id' => $address->id,
                'payment_method' => $request->payment_method,
            ]);

            $item->update([
                'status' => Item::STATUS_SOLD,
            ]);
        });

        session()->forget([
            'purchase_address',
            'payment_method',
        ]);

        return redirect()->route('items.index');
    }
}
