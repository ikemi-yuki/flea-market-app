<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_送付先住所変更画面にて登録した住所が商品購入画面に反映される()
    {
        $seller = User::factory()->create([
            'profile_completed' => true,
        ]);

        $buyer = User::factory()->withProfile()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($buyer);

        $response = $this->get(route('purchase.address.edit', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $this->post(route('purchase.address.update', ['item_id' => $item->id]),[
            'shipping_post_code' => '123-4567',
            'shipping_address' => '東京都渋谷区1-2-3',
            'shipping_building' => 'テストマンション101号室',
        ]);

        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-2-3');
        $response->assertSee('テストマンション101号室');
    }

    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        $seller = User::factory()->create([
            'profile_completed' => true,
        ]);

        $buyer = User::factory()->withProfile()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($buyer);

        $response = $this->get(route('purchase.address.edit', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $this->post(route('purchase.address.update', ['item_id' => $item->id]),[
            'shipping_post_code' => '123-4567',
            'shipping_address' => '東京都渋谷区1-2-3',
            'shipping_building' => 'テストマンション101号室',
        ]);

        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $paymentMethod = Purchase::PAYMENT_METHOD_CONVENIENCE_STORE;

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]),[
            'payment_method' => $paymentMethod,
        ]);

        $response->assertRedirect(route('items.index'));

        $purchase = Purchase::first();

        $this->assertNotNull($purchase);

        $this->assertDatabaseHas('addresses', [
            'purchase_id' => $purchase->id,
            'shipping_post_code' => '123-4567',
            'shipping_address' => '東京都渋谷区1-2-3',
            'shipping_building' => 'テストマンション101号室',
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
        ]);

        $this->assertNotNull($purchase->address);
        $this->assertEquals('東京都渋谷区1-2-3', $purchase->address->shipping_address);
    }
}
