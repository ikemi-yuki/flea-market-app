<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねした商品だけが表示される()
    {
        $user = User::factory()->create([
            'profile_completed' => true,
        ]);

        $likedItem = Item::factory()->create([
            'name' => 'いいねした商品',
        ]);

        $notLikedItem = Item::factory()->create([
            'name' => 'いいねしていない商品',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get(route('items.index', ['tab' => 'mylist']));
        $response->assertStatus(200);

        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_購入済み商品はSoldと表示される()
    {
        $user = User::factory()->create([
            'profile_completed' => true,
        ]);

        $likedItem = Item::factory()->create([
            'name' => 'いいねした購入済み商品',
            'status' => Item::STATUS_SOLD,
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get(route('items.index', ['tab' => 'mylist']));
        $response->assertStatus(200);

        $response->assertSee('いいねした購入済み商品');
        $response->assertSee('Sold');
    }

    public function test_未認証の場合は何も表示されない()
    {
        $user = User::factory()->create([
            'profile_completed' => true,
        ]);

        $likedItem = Item::factory()->create([
            'name' => 'いいねした商品',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->get(route('items.index', ['tab' => 'mylist']));
        $response->assertStatus(200);

        $response->assertDontSee('いいねした商品');
    }
}
