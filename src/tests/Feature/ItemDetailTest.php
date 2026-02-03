<?php

namespace Tests\Feature;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_必要な情報が表示される()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'profile_completed' => true,
        ]);

        $profileImage = UploadedFile::fake()->create('profile.jpg', 100, 'image/jpeg');
        $profilePath = $profileImage->store('profiles', 'public');

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'name' => '山田',
            'icon_path' => $profilePath,
        ]);

        $category = Category::create([
            'name' => '家電',
        ]);

        $itemImage = UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg');
        $itemPath = $itemImage->store('items', 'public');

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'image_path' => $itemPath,
            'brand' => 'テストブランド',
            'description' => '商品の説明文です',
            'price' => 3000,
            'condition' => Item::CONDITION_NO_NOTICEABLE_DAMAGE,
        ]);

        $item->categories()->attach([
            $category->id,
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'とてもいい商品ですね',
        ]);

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('テスト商品');
        $response->assertSee('<img', false);
        $response->assertSee($itemPath);
        $response->assertSee('テストブランド');
        $response->assertSee('3,000');
        $response->assertSee('<span class="like-count">1</span>', false);
        $response->assertSee('<span class="comment-count">1</span>', false);
        $response->assertSee('商品の説明文です');
        $response->assertSee('家電');
        $response->assertSee('目立った傷や汚れなし');
        $response->assertSee('<h3 class="comment">コメント(1)</h3>', false);
        $response->assertSee('<img', false);
        $response->assertSee($profilePath);
        $response->assertSee('山田');
        $response->assertSee('とてもいい商品ですね');
    }

    public function test_複数選択されたカテゴリが表示される()
    {
        $category1 = Category::create([
            'name' => 'ファッション',
        ]);

        $category2 = Category::create([
            'name' => 'メンズ',
        ]);

        $item = Item::factory()->create();

        $item->categories()->attach([
            $category1->id,
            $category2->id,
        ]);

        $response = $this->get(route('items.detail', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
    }
}
