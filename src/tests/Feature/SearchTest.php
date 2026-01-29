<?php

namespace Tests\Feature;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品名で部分一致検索ができる()
    {
        $matchingItem = Item::factory()->create([
            'name' => '赤いシャツ',
        ]);

        $notMatchingItem = Item::factory()->create([
            'name' => '青いズボン',
        ]);

        $response = $this->get(route('items.index', ['keyword' => '赤']));
        $response->assertStatus(200);

        $response->assertSee('赤いシャツ');
        $response->assertDontSee('青いズボン');
    }

    public function test_検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create([
            'profile_completed' => true,
        ]);

        $matchingItem = Item::factory()->create([
            'name' => '赤いシャツ',
        ]);

        $notMatchingItem = Item::factory()->create([
            'name' => '青いズボン',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $matchingItem->id,
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $notMatchingItem->id,
        ]);

        $response = $this->actingAs($user)->get(route('items.index', ['keyword' => '赤']));
        $response->assertStatus(200);

        $response->assertSee('赤いシャツ');
        $response->assertDontSee('青いズボン');

        $response = $this->actingAs($user)->get(route('items.index', ['tab' => 'mylist', 'keyword' => '赤']));
        $response->assertStatus(200);

        $response->assertSee('赤いシャツ');
        $response->assertDontSee('青いズボン');
    }
}
