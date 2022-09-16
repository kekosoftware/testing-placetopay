<?php

namespace App\Patterns\StrategyPlaceToPay;

use App\Models\Order;
use App\Models\Transaction;
use App\Patterns\StrategyPlaceTo\P2PInterface;

class P2PContext
{
    private $P2PInterface;

    public function __construct(P2PInterface $P2PInterface)
    {
        $this->$P2PInterface = $P2PInterface;
    }

    public function create(P2PInterface $P2PInterface)
    {
        return new static($P2PInterface);
    }

    public function pay(Order $order)
    {
        return $this->P2PInterface->pay($order);
    }

    public function getInfoPay(Transaction $transaction)
    {
        return $this->P2PInterface->getInfoPay($transaction);
    }
}
