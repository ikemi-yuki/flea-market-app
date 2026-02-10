<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class PurchaseAddressController extends Controller
{
    public function edit(Request $request, $item_id)
    {
        session(['payment_method' => $request->payment_method]);

        $item = Item::findOrFail($item_id);

        return view('address', compact('item'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        session([
            'purchase_address' => [
                'post_code' => $request->shipping_post_code,
                'address' => $request->shipping_address,
                'building' => $request->shipping_building,
            ]
        ]);

        if ($request->filled('payment_method')) {
            session(['payment_method' => $request->payment_method]);
        }

        return redirect()->route('purchase.show', $item_id);
    }
}
