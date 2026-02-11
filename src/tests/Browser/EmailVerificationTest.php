<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EmailVerificationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_メール認証誘導画面で認証はこちらからボタンを押下するとメール認証サイトに遷移する()
    {
        $user = User::factory()->unverified()->create();

        $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
                ->visit('/email/verify')
                ->click('.verify__button')
                ->assertUrlIs('http://localhost:8025/');
        });
    }
}
