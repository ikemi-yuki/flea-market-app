<?php

namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_メールアドレスが入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post(route('login'), [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSee('メールアドレスを入力してください');
    }

    public function test_パスワードが入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post(route('login'), [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSee('パスワードを入力してください');
    }

    public function test_入力情報が間違っている場合バリデーションメッセージが表示される()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->followingRedirects()->post(route('login'), [
            'email' => 'wrong@example.com',
            'password' => '123password',
        ]);

        $response->assertSee('ログイン情報が登録されていません');
    }

    public function test_正しい情報が入力された場合ログイン処理が実行される()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
    }
}
