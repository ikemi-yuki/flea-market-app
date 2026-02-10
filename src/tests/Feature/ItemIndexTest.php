<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品を取得できる()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get(route('items.index'));
        $response->assertStatus(200);

        $items = Item::all();
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_購入済み商品はSoldと表示される()
    {
        Item::factory()->create([
            'name' => '購入済みの商品',
            'status' => Item::STATUS_SOLD,
        ]);

        $response = $this->get(route('items.index'));
        $response->assertStatus(200);

        $response->assertSee('購入済みの商品');
        $response->assertSee('Sold');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $loginUser = User::factory()->create([
            'profile_completed' => true,
        ]);

        Item::factory()->create([
            'user_id' => $loginUser->id,
            'name' => '自分の商品',
        ]);

        Item::factory()->create([
            'name' => '他人の商品',
        ]);

        $response = $this->actingAs($loginUser)->get(route('items.index'));
        $response->assertStatus(200);

        $response->assertSee('他人の商品');
        $response->assertDontSee('自分の商品');
    }
}
