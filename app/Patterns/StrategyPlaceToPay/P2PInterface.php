<?php

namespace App\Patterns\StrategyPlaceToPay;

interface P2PInterface
{
    public function pay(Order $order);

    public function getInfoPay(Transaction $transaction);
}
