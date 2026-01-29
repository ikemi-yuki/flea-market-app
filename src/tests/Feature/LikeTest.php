<?php

namespace Tests\Feature;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねアイコンを押下することでいいねした商品として登録できる()
    {
        $user = User::factory()-> create([
            'profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user)
            ->get(route('items.detail', ['item_id' => $item->id]))
            ->assertStatus(200);

        $this->post(route('items.like', ['item_id' => $item->id]))
            ->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('<span class="like-count">1</span>', false);
    }

    public function test_追加済みのアイコンは色が変化する()
    {
        $user = User::factory()-> create([
            'profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user)
            ->get(route('items.detail', ['item_id' => $item->id]))
            ->assertStatus(200);

        $this->post(route('items.like', ['item_id' => $item->id]))
            ->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('heart-logo_pink.png');
    }

    public function test_再度いいねアイコンを押下することによっていいねを解除できる()
    {
        $user = User::factory()-> create([
            'profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user)
            ->get(route('items.detail', ['item_id' => $item->id]))
            ->assertStatus(200);

        $this->post(route('items.like', ['item_id' => $item->id]))
            ->assertRedirect();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));

        $response->assertSee('<span class="like-count">0</span>', false);
    }
}
