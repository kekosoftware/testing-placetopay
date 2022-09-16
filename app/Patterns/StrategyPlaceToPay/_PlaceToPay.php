<?php

namespace App\Patterns\StrategyPlaceToPay;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Transaction;
use App\Traits\PaymentTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Dnetix\Redirection\PlacetoPay as PlacetoPayLibrary;

class PlaceToPay implements P2PInterface
{
    use PaymentTrait;

    /**
     * @var array status list
     */
    public $statusList;

    /**
     * @var array status list of orders
     */
    public $statusListOrder;

    /**
     * @var Transaction model
     */
    public $transaction;

    /**
     * @var PlaceToPayLibrary Object
     */
    public $placeToPayLibrary;

    /**
     * Constructor
     */
    public function __construct(PlaceToPayLibrary $placeToPayLibrary, Transaction $transaction)
    {
        $this->placeToPayLibrary = $placeToPayLibrary;
        $this->transaction = $transaction;
    }

    /**
     * Create the transaction
     */
    public function pay(Order $order)
    {
        DB::beginTransaction();

        try {
            $response = $this->createPay($order);

            DB::commit();

            return (Object) [
                'success' => true,
                'url' => $response->processUrl(),
            ];
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            DB::rollback();
            return (object) [
                'success' => false,
                'exception' => $th,
            ];
        } catch (PlacetoPayException $e) {
            Log::info($e->getMessage());
            DB::rollback();
            return (object) [
                'success' => false,
                'exception' => $e,
            ];
        }
    }

    /**
     * Create the pay
     */
    public function createPay(Order $order)
    {
        $uuid = $this->getUuid();
        $reference = $this->getReference($order->id);
        $request = $this->getRequestData($order, $reference, $uuid);
        $response = $this->placeToPayLibrary->request($request);

        if(!$response->isSuccessful()) {
            throw new \Exception("Se ha producido un error en la transacción (".$response->status()->message().").");
        }

        $transaction = $this->transaction->store([
            'order_id' => $order->id,
            'uuid' => $uuid,
            'status' => 'CREATED',
            'reference' => $reference,
            'url' => $response->processUrl(),
            'requestId' => $response->requestId(),
            'getway' => 'placeToPay',
        ]);

        if(!$transaction) {
            throw new \Exception('Se ha producido un error al guardr la transaction.');
        }

        if (!$transaction->attachStates(
            [
                [
                    'transaction_id' => $transaction->id,
                    'status' => 'CREATED',
                    'data' => json_encode($response->toArray()),
                ]
            ]
        )) {
            throw new \Exception('Se ha producido un error al almacenar el estado de la transaccion.');
        }

        return $response;
    }

    /**
     * Validate and update state of the transaction
     */
    public function getInfoPay(Transaction $transaction)
    {
        try {
            $response =  $this->placeToPayLibrary->query($transaction->requestId);
            $status = $response->status()->status();

            if (!$status) {
                throw new \Exception('Se ha producido un error con el estado');
            }

            if  ($transaction->getAttributeValue('status') != $status) {
                if (!$transaction->edit(
                    [
                        'status' => $status,
                    ]
                )) {
                    throw new \Exception('Se ha producido un error con estado de la transaction');
                }

                if (!$transaction->attachStates (
                    [
                        [
                            'status' => $status,
                            'data' => json_encode($response->toArray()),
                        ]
                    ]
                )) {
                    throw new \Exception('Se ha producido un error al almacenar el estado de la transaction.');
                }

                if (!$transaction->updateOrder(['status' => $status,])) {
                    throw new \Exception('Se ha producido un error al actualizar el estado de la orden.');
                }
            }

            return (object) [
                'success' => true,
                'data' => [
                    'status' => $response->status()->status(),
                    'message' => $response->status()->message(),
                ]
            ];

        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return (Object) [
                'success' => false,
                'exception' => $th,
            ];
        } catch (PlacetoPayException $e) {
            Log::info($e->getMessage());
            DB::rollback();
            return (Object) [
                'success' => false,
                'exception' => $e,
            ];
        }
    }


    private function getRequestData(Order $order, $reference, $uuid): array
    {
        $urlRecive = route("transactions.receive", ["gateway" => "placeToPay",'uuid' => $uuid]);

        return [
            "locale" => "es_CO",
            "buyer" => $this->getBuyer(),
            "payment" => [
                "reference" => $reference,
                "description" => "Compra de (".$order->product->name.") ",
                "amount" => [
                    "currency" => "COP",
                    "total" => $order->total,
                    "taxes" => [
                        [
                            "kind" => "iva",
                            "amount" => 0.00
                        ]
                    ]
                ],
                "items" => [
                    [
                        "name" => $order->product->name,
                        "price" => $order->total
                    ]
                ],
                "allowPartial" => false,
            ],
            "expiration" => Carbon::now()->addMinutes(env('EXPIRED_TIME'))->format("c"),
            "ipAddress" => request()->ip(),
            "userAgent" => request()->header('user-agent'),
            "returnUrl" => $urlRecive,
            "cancelUrl" => $urlRecive,
            "skipResult" => false,
            "noBuyerFill" => false,
            "captureAddress" => false,
            "paymentMethod" => null
        ];
    }

    /**
     * get the buyer data.
     */
    private function getBuyer(): array
    {
        $user = auth()->user();

        return [
            "name" => $user->name,
            "email" => $user->email,
            "mobile" => $user->mobile,
        ];
    }
}
