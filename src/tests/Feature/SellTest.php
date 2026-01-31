<?php

namespace Tests\Feature;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SellTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品出品画面にて必要な情報が保存できる()
    {
        // 画像保存用
        Storage::fake('public');

        $user = User::factory()->create([
            'profile_completed' => true,
        ]);

        $category1 = Category::create([
            'name' => 'ファッション',
        ]);

        $category2 = Category::create([
            'name' => 'メンズ',
        ]);

        $image = UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg');

        $this->actingAs($user);

        $response = $this->get(route('sell.index'));
        $response->assertStatus(200);

        $response = $this->post(route('sell.store'),[
            'image_path' => $image,
            'categories' => [$category1->id, $category2->id],
            'condition' => 1, // 良好
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 5000,
        ]);

        $response->assertRedirect(route('mypage.index'));

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'condition' => 1,
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 5000,
            'status' => Item::STATUS_ON_SALE,
        ]);

        $item = Item::first();
        Storage::disk('public')->assertExists($item->image_path);
        $this->assertNotNull($item->image_path);
        $this->assertStringContainsString('items', $item->image_path);

        $this->assertDatabaseHas('item_category', [
            'item_id' => $item->id,
            'category_id' => $category1->id,
        ]);

        $this->assertDatabaseHas('item_category', [
            'item_id' => $item->id,
            'category_id' => $category2->id,
        ]);
    }
}
