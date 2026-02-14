<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use UnexpectedValueException;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $stripeSignature = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $stripeSignature,
                $secret
            );
        } catch (UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        Log::info('Stripe webhook received', [
            'type' => $event->type,
        ]);

        switch ($event->type) {

            case 'checkout.session.completed':

                $session = $event->data->object;

                if ($session->payment_status !== 'paid') {
                    break;
                }

                $itemId = $session->metadata->item_id ?? null;

                if (!$itemId) {
                    break;
                }

                DB::transaction(function () use ($itemId) {

                    $item = Item::find($itemId);

                    if (!$item) {
                        return;
                    }

                    if ($item->status === Item::STATUS_SOLD) {
                        return;
                    }

                    $item->update([
                        'status' => Item::STATUS_SOLD
                    ]);
                });

                break;
        }

        return response('Webhook handled', 200);
    }
}
