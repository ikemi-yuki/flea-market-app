<?php

namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログアウトができる()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        $this->assertAuthenticated();

        $response = $this->post(route('logout'));

        $this->assertGuest();
    }
}
