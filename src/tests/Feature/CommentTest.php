<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->withProfile()->create();

        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('<span class="comment-count">0</span>', false);

        $this->post(route('items.comment', ['item_id' => $item->id]), [
            'content' => 'とてもいい商品ですね',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'とてもいい商品ですね'
        ]);

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('<span class="comment-count">1</span>', false);
    }

    public function test_ログイン前のユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('items.comment', ['item_id' => $item->id]), [
            'content' => 'とてもいい商品ですね',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_コメントが入力されていない場合バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('items.comment', ['item_id' => $item->id]), [
            'content' => '',
        ]);

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('コメントを入力してください');
    }

    public function test_コメントが255字以上の場合バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $longComment = str_repeat('あ',256);

        $this->actingAs($user);

        $response = $this->post(route('items.comment', ['item_id' => $item->id]), [
            'content' => $longComment,
        ]);

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('コメントは255文字以内で入力してください');
    }
}
