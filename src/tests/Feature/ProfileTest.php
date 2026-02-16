<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_プロフィール画面にて必要な情報が取得できる()
    {
        //プロフィール画像表示用
        Storage::fake('public');

        $seller = User::factory()->create([
            'profile_completed' => true,
        ]);

        $buyer = User::factory()->create([
            'profile_completed' => true,
        ]);

        $profileImage = UploadedFile::fake()->create('profile.jpg', 100);
        $profilePath = $profileImage->store('profile_icons', 'public');

        $buyerProfile = Profile::factory()->create([
            'user_id' => $buyer->id,
            'name' => '山田',
            'icon_path' => $profilePath,
        ]);

        Item::factory()->create([
            'user_id' => $buyer->id,
            'name' => '出品商品',
        ]);

        $purchasedItem = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入商品',
            'status' => Item::STATUS_SOLD,
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $purchasedItem->id,
            'payment_method' => Purchase::PAYMENT_METHOD_CARD,
        ]);

        $this->actingAs($buyer);

        $response = $this->get(route('mypage.index'));
        $response->assertStatus(200);

        $response->assertSee('src="' . $buyerProfile->icon_url . '"', false);
        $response->assertSee('山田');

        $sellResponse = $this->get(route('mypage.index', ['page' => 'sell']));
        $sellResponse->assertStatus(200);

        $sellResponse->assertSee('出品商品');
        $sellResponse->assertDontSee('購入商品');

        $buyResponse = $this->get(route('mypage.index', ['page' => 'buy']));
        $buyResponse->assertStatus(200);

        $buyResponse->assertSee('購入商品');
        $buyResponse->assertDontSee('出品商品');
    }

    public function test_プロフィール変更画面にて変更項目が初期値として過去設定されている()
    {
        //プロフィール画像表示用
        Storage::fake('public');

        $user = User::factory()->create([
            'profile_completed' => true,
        ]);

        $profileImage = UploadedFile::fake()->create('profile.jpg', 100);
        $profilePath = $profileImage->store('profile_icons', 'public');

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'name' => '山田',
            'icon_path' => $profilePath,
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストマンション101号室',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertStatus(200);

        $response->assertSee('src="' . $profile->icon_url . '"', false);
        $response->assertSee('山田');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-2-3');
        $response->assertSee('テストマンション101号室');
    }
}