<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

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

    public function updatePayment(Request $request, $item_id)
    {
        session(['payment_method' => $request->payment_method]);

        return redirect()->route('purchase.show', $item_id);
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        if ($item->status === 2) {
            abort(403, 'この商品は購入済みです');
        }

        $addressData = session('purchase_address')
            ?? [
                'post_code' => $user->profile->post_code,
                'address' => $user->profile->address,
                'building' => $user->profile->building,
            ];

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
            'status' => 2,
        ]);

        session()->forget(['purchase_address', 'payment_method']);

        return redirect()->route('items.index');
    }
}
