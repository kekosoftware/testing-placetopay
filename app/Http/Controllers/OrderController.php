<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Traits\PaymentTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Support\Facades\Cookie;
use Dnetix\Redirection\Entities\Status;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    use PaymentTrait;

    public $productId = '';
    public $orderId = '';
    public $order;

    /**
     * Constructor Order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = DB::table('orders')
            ->join('products', 'products.id', 'orders.product_id')
            ->join('transactions', 'transactions.order_id', 'orders.id')
            ->paginate(10);

        return view('orderlist', compact('orders'));
    }

    /**
     * Display a details of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function details($id)
    {
        $orders = DB::table('orders')
            ->join('products', 'products.id', 'orders.product_id')
            ->join('transactions', 'transactions.order_id', 'orders.id')
            ->where('orders.id', $id)
            ->get();

    //dd((Object)$orders, $orders[0]->requestId);
        Cookie::queue('requestId', $orders[0]->requestId, 30);
        return view('orderdetails', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $nonce = $this->getNonce();
            $seed = $this->getSeed();
            $myProduct = Product::find(intval($request->product_id));
            $productId = $myProduct->id;

            $auth = array(
                'login' => env('P2P_LOGIN'),
                'tranKey' => base64_encode(sha1($nonce . $seed . env('P2P_SECRET_KEY'), true)),
                'nonce' => base64_encode($nonce),
                'seed' => $seed,
            );

            $amount = array(
                'currency' => 'COP',
                'total' => $myProduct->price,
            );

            $payment = array(
                'reference' => $this->getReference(),
                'description' => $myProduct->name,
                'amount' => $amount,
                "allowPartial" => false,
            );

            $payload = array(
                "locale" => "es_CO",
                "auth" => $auth,
                "payment" => $payment,
                "expiration" => Carbon::now()->addMinutes(
                    env('EXPIRED_TIME')
                )->format("c"),
                "returnUrl" => url('/') . "/show/" . base64_encode($request->product),
                "ipAddress" => request()->ip(),
                "userAgent" => "PlacetoPay Sandbox",

            );

            $result = json_decode(
                    (
                        Http::post(
                            env('P2P_TRAN_URL').'redirection/api/session',
                            $payload
                        )
                    )->body()
                );

            if ($result->status->status == "OK") {
                $dataOrder = [];
                $dataOrder['customer_name'] = $request->customer_name;
                $dataOrder['customer_email'] = $request->customer_email;
                $dataOrder['customer_mobile'] = $request->customer_mobile;
                $dataOrder['status'] = 'CREATED';
                $dataOrder['total'] = $request->total;
                $dataOrder['product_id'] = $request->product_id;
                $dataOrder['created_at'] = DB::raw('CURRENT_TIMESTAMP');

                DB::table('orders')->insert($dataOrder);
                $this->orderId = Order::latest('id')->first();

                $dataTrans = [];
                $dataTrans['uuid'] = $this->getUuid();
                $dataTrans['status'] = 'CREATED';
                $dataTrans['reference'] = $this->getReference();
                $dataTrans['status'] = 'CREATED';
                $dataTrans['url'] = $result->processUrl;
                $dataTrans['gateway'] = 'placeToPay';
                $dataTrans['requestId'] = $result->requestId;
                $dataTrans['order_id'] = $this->orderId->id;
                $dataTrans['created_at'] = DB::raw('CURRENT_TIMESTAMP');

                DB::table('transactions')->insert($dataTrans);

                Cookie::queue('requestId', $result->requestId, 30);

                DB::commit();

                return Redirect::to("$result->processUrl");
            }

            DB::rollback();
            return Redirect::back()->withErrors(['error' => 'La petición ha fallado: '.$result->status->message]);

        } catch (Throwable $e) {
            DB::rollback();
            return Redirect::back()->withErrors(['error' => 'Se ha producido un error al generar la orden.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        DB::beginTransaction();
        try {
            $requestId = Cookie::get('requestId');

            $nonce = $this->getNonce();
            $seed = $this->getSeed();

            $auth = array(
                'login' => env('P2P_LOGIN'),
                'tranKey' => base64_encode(sha1($nonce . $seed . env('P2P_SECRET_KEY'), true)),
                'nonce' => base64_encode($nonce),
                'seed' => $seed,
            );

            $data = array(
                "auth" => $auth,
            );

            $result = json_decode(
                (
                    Http::post(
                        env('P2P_TRAN_URL').'redirection/api/session/'.$requestId,
                        $data
                    )
                )->body()
            );

            if (isset($result)) {
                $dataTrans = [];
                $dataTrans['status'] = $result->status->status;
                $dataTrans['updated_at'] = DB::raw('CURRENT_TIMESTAMP');

                DB::table('transactions')
                    ->where('requestId', $requestId)
                    ->update($dataTrans);

                $dataOrder = [];

                if($result->status->status == Status::ST_APPROVED) $dataOrder['status'] = 'PAYED';
                if($result->status->status == Status::ST_REJECTED) $dataOrder['status'] = 'REJECTED';
                $dataOrder['updated_at'] = DB::raw('CURRENT_TIMESTAMP');

                $transacReg = DB::table('transactions')
                            ->where('requestId', $requestId)
                            ->first();

                DB::table('orders')
                    ->where('id', $transacReg->order_id)
                    ->update($dataOrder);

                $orderReg = DB::table('orders')
                    ->where('id', $transacReg->order_id)
                    ->first();
            }

            DB::commit();

            return view('payment')
                    ->with('result', $result)
                    ->with('transac', $transacReg)
                    ->with('order', $orderReg);

        } catch (Throwable $e) {
            DB::rollback();
            return Redirect::back()->withErrors(['error' => 'Se ha producido un error al consultar la orden.']);
        }
    }
}
