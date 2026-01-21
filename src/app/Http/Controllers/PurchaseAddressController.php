<?php

namespace App\Http\Controllers;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseAddressController extends Controller
{
    public function edit($item_id)
    {
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

        return redirect()->route('purchase.show', $item_id);
    }
}
