<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
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

        if ($address === null) {
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

        if ($item->status !== Item::STATUS_ON_SALE) {
            abort(403, 'この商品は購入できません');
        }

        $addressData = session('purchase_address')
            ?? [
                'post_code' => $user->profile->post_code,
                'address' => $user->profile->address,
                'building' => $user->profile->building,
            ];

        $paymentMethod = (int) $request->payment_method;

        DB::transaction(function () use ($user, $item, $addressData, $paymentMethod) {
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_method' => $paymentMethod,
            ]);

            $purchase->address()->create([
                'shipping_post_code' => $addressData['post_code'],
                'shipping_address' => $addressData['address'],
                'shipping_building' => $addressData['building'],
            ]);

            // テスト環境ではStripe通信は行わず、購入完了として扱う
            $isTesting = app()->environment('testing');

            $item->update([
                'status' => $isTesting || $paymentMethod !== Purchase::PAYMENT_METHOD_CARD
                    ? Item::STATUS_SOLD
                    : Item::STATUS_IN_PROGRESS,
            ]);
        });

        session()->forget([
            'purchase_address',
            'payment_method',
        ]);

        if (app()->environment('testing')) {
            return redirect()->route('items.index');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = CheckoutSession::create([
            'payment_method_types' => $paymentMethod === Purchase::PAYMENT_METHOD_CARD ? ['card'] : ['konbini'],

            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],

            'mode' => 'payment',

            'metadata' => [
                'item_id' => $item->id,
                'user_id' => $user->id,
            ],

            'success_url' => $paymentMethod === Purchase::PAYMENT_METHOD_CARD
            ? route('purchase.success', ['item_id' => $item->id])
            : route('items.index'),

            'cancel_url' => route('items.index'),
        ]);

        return redirect($session->url);
    }

    public function success($item_id)
    {
        return redirect()->route('items.index');
    }
}
