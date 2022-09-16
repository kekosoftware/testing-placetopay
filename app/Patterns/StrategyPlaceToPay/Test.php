<?php
namespace App\Patterns\StrategyPlaceToPay;


use App\Models\Order;
use App\Models\Transaction;
use App\Patterns\StrategyPlaceToPay\P2PInterface;

class Test implements P2PInterface
{
    public function pay(Order $order)
    {
        return (object) [
            'success' => true,
            'url' => 'https://github.com/kekosoftware/api-weather',
        ];
    }

    public function getInfoPay(Transaction $transaction)
    {
        return (Object) [
            "success" => true,
            "data" => [
                "status" => 'CREATED',
                "message" => 'Pago creado.',
            ]
        ];
    }
}
