<?php

namespace Tests\Browser;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PurchasePaymentMethodTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_小計画面で変更が反映される()
    {
        $user = User::factory()->withProfile()->create();

        $item = Item::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $item) {
            $browser->loginAs($user)
                ->visit(route('purchase.show', ['item_id' => $item->id]))
                ->assertSee('選択してください')
                ->click('.payment__select-label')
                ->pause(300)
                ->click('.payment__select-item[data-value="2"]')
                ->assertSeeIn('#subtotal-payment', 'カード支払い');
        });
    }
}
