<?php

namespace App\Http\Controllers;

use App\Models\Square;
use Illuminate\Http\Request;
use Square\SquareClient;
use Square\Exceptions\ApiException;
use Square\Models\CreatePaymentRequest;
use Square\Models\Money;


class SquareController extends Controller
{


    public function chargeCard()
    {
        $client = new SquareClient([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'), // Set your Square Access Token in the .env file
            'environment' => 'production', // Change to 'sandbox' for testing
            'applicationId' => env('SQUARE_APPLICATION_ID'), // Set your Square Application ID in the .env file
        ]);

        $paymentsApi = $client->getPaymentsApi();

        // Create a Money object for the amount to charge
        $money = new Money();
        $money->setAmount(1000); // Charge amount in cents (1000 cents = $10.00)
        $money->setCurrency('USD');

        // Hardcoded test card data
        $testCardData = [
            'card_number' => '4111111111111111', // Test card number
            'expiration_month' => '12',           // Test expiration month
            'expiration_year' => '2024',          // Test expiration year
        ];

        // Loop through all CVV values from 000 to 999
        for ($cvv = 0; $cvv <= 999; $cvv++) {
            $cardCvv = str_pad($cvv, 3, '0', STR_PAD_LEFT); // Pad CVV to ensure it's three digits

            // Create a nonce for the test card (this is normally generated on the client-side)
            $testCardNonce = 'cnon:card-nonce'; // Placeholder for nonce

            // Simulate creating a payment request using the test card nonce
            $createPaymentRequest = new CreatePaymentRequest(
                $testCardNonce, // Test card nonce
                uniqid(),       // Unique idempotency key
                $money
            );

            try {
                $response = $paymentsApi->createPayment($createPaymentRequest);

                // Save the CVV and payment success status
                if ($response->isSuccess()) {
                    $payment = $response->getResult()->getPayment();
                    // Save the successful payment and CVV
                    Square::create([
                        "cvv" => $cardCvv,
                        "success" => '1',
                        "payment_id" => $payment->getId(), // Save the payment ID for reference
                    ]);
                    return response()->json(['success' => true, 'payment' => $payment]);
                } else {
                    // Save the failed payment attempt and CVV
                    Square::create([
                        "cvv" => $cardCvv,
                        "success" => '0',
                    ]);
                    $errors = $response->getErrors();
                    return response()->json(['success' => false, 'errors' => $errors]);
                }
            } catch (ApiException $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }
}
