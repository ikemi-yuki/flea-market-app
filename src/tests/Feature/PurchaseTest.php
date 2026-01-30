<?php

namespace Tests\Feature;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_購入するボタンを押下すると購入が完了する()
    {
        $seller = User::factory()->create([
            'profile_completed' => true,
        ]);

        $buyer = User::factory()->withProfile()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $addressData = [
            'post_code' => $buyer->profile->post_code,
            'address' => $buyer->profile->address,
            'building' => $buyer->profile->building,
        ];

        $this->actingAs($buyer);

        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee(number_format($item->price));

        $paymentMethod = Purchase::PAYMENT_METHOD_CONVENIENCE_STORE;
        $response = $this->post(route('purchase.store', ['item_id' => $item->id]),[
            'payment_method' => $paymentMethod,
        ]);

        $response->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('addresses', [
            'user_id' => $buyer->id,
            'shipping_post_code' => $addressData['post_code'],
            'shipping_address' => $addressData['address'],
            'shipping_building' => $addressData['building'],
        ]);

        $address = Address::first();

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
        ]);

        $this->assertEquals(Item::STATUS_SOLD, $item->fresh()->status);
    }

    public function test_購入した商品は商品一覧画面にてSoldと表示される()
    {
        $seller = User::factory()->create([
            'profile_completed' => true,
        ]);

        $buyer = User::factory()->withProfile()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入した商品',
        ]);

        $this->actingAs($buyer);

        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $paymentMethod = Purchase::PAYMENT_METHOD_CONVENIENCE_STORE;
        $response = $this->post(route('purchase.store', ['item_id' => $item->id]),[
            'payment_method' => $paymentMethod,
        ]);

        $response->assertRedirect(route('items.index'));

        $response = $this->get(route('items.index'));
        $response->assertStatus(200);

        $response->assertSee('購入した商品');
        $response->assertSee('Sold');
    }

    public function test_プロフィール画面の購入した商品一覧に追加される()
    {
        $seller = User::factory()->create([
            'profile_completed' => true,
        ]);

        $buyer = User::factory()->withProfile()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($buyer);

        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $paymentMethod = Purchase::PAYMENT_METHOD_CONVENIENCE_STORE;
        $response = $this->post(route('purchase.store', ['item_id' => $item->id]),[
            'payment_method' => $paymentMethod,
        ]);

        $response->assertRedirect(route('items.index'));

        $response = $this->get(route('mypage.index', ['page' => 'buy']));
        $response->assertStatus(200);

        $response->assertSee($item->name);
    }
}
