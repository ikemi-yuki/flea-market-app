<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_会員登録後認証メールが送信される()
    {
        Notification::fake();

        $response = $this->followingRedirects()->post(route('register'), [
            'name' => '山田太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    public function test_メール認証誘導画面で認証はこちらからボタンを押下するとメール認証サイトに遷移する()
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $response = $this->get('/email/verify');
        $response->assertStatus(200);

        $response->assertSee('認証はこちらから');
        $response->assertSee('http://localhost:8025/');
    }

    public function test_メール認証サイトのメール認証を完了するとプロフィール設定画面に遷移する()
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
        );

        $response = $this
        ->actingAs($user)
        ->followingRedirects()
        ->get($verificationUrl);

        $response->assertStatus(200);
        $response->assertSee('プロフィール設定');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
