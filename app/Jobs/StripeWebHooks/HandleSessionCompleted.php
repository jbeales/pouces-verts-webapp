<?php

namespace App\Jobs\StripeWebHooks;

use App\Member;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Models\WebhookCall;

class HandleSessionCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * @var array $payload This is cast as an Array by the WebhookCall model
         */
        $payload = $this->webhookCall->payload;
        if($payload['object'] !== 'event') {
            return;
        }

        if($payload['type'] !== 'checkout.session.completed') {
            return;
        }

        if(isset($payload['data']) && isset($payload['data']['object'])) {

            // Set some defaults & avoid missing offset errors.
            $event = array_merge([
                'client_reference_id' => '',
                'amount_total' => 0,
                'payment_intent' => '',
                'payment_status' => ''
            ], $payload['data']['object']);

            if($event['payment_status'] !== 'paid') {
                return;
            }

            $reference_id_parts = explode('-', $event['client_reference_id'] );
            if(count($reference_id_parts) === 2) {
                $email = trim($reference_id_parts[1]);
                // Try to get the email.
                try {
                    $member = new Member();
                    $member->load_from_email($email);
                    if(!$member->has_paid()) {
                        $member->record_payment($event['amount_total'], $event['payment_intent']);
                    }
                } catch (Exception $e) {
                    Log::info("Member $email paid with payment {$event['payment_intent']} but could not be located in webhook.");
                }
            } else {
                Log::info("Couldn't parse client email from reference ID {$event['client_reference_id']}");
            }


        }
    }
}
