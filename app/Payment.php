<?php

namespace App;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use App\Patterns\StrategyPlaceToPay\Test;
use App\Patterns\StrategyPlaceToPay\P2PContext;
use App\Patterns\StrategyPlaceToPay\PlaceToPay;

class Payment
{

    /**
     * @var array $paymentMethodsEnable Metodos de pagos habilitados.
     */
    private static $paymentMethodsEnable=[
        "place_to_pay" => PlaceToPay::class,
        "test" => Test::class,
    ];

    private function createStrategy(string $typePay)
    {
        try {
            if (isset(self::$paymentMethodsEnable[$typePay])) {
                if ($typePay == "placeToPay") {
                    return P2PContext::create(
                        new self::$paymentMethodsEnable[$typePay](

                            new \Dnetix\Redirection\PlacetoPay([
                                'login' => env('P2P_LOGIN'), // Provided by PlacetoPay
                                'tranKey' => env('P2P_TRAN_KEY'), // Provided by PlacetoPay
                                'baseUrl' => env('P2P_TRAN_URL'),
                                'timeout' => 10 // (optional) 15 by default
                            ]),

                            new Transaction()
                        )
                    );
                }

                return Context::create(new self::$paymentMethodsEnable[$typePay]);
            }
        } catch (\Dnetix\Redirection\Exceptions\PlacetoPayException $e) {
            Log::info($e->getMessage());
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }

        return false;
    }

    public function pay(string $typePay, Order $order)
    {
        if ($staregy = $this->createStrategy($typePay)) {
            return $staregy->pay($order);
        }

        return false;
    }

    public function getInfoPay(Transaction $transaction)
    {
        if ($staregy = $this->createStrategy($transaction->gateway)) {
            return $staregy->getInfoPay($transaction);
        }

        return false;
    }
}
