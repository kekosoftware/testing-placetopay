<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Traits\PaymentTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    use PaymentTrait;

    public $productId = '';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {
        $nonce = $this->getNonce();
        $seed = $this->getSeed();
        $myProduct = Product::find($request->product);
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
//dd($result);
        return Redirect::to($result->processUrl);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd($id);
        $nonce = $this->getNonce();
        $seed = $this->getSeed();
        $myProduct = Product::find($request->product);

        $auth = array(
            'login' => env('P2P_LOGIN'),
            'tranKey' => base64_encode(sha1($nonce . $seed . env('P2P_SECRET_KEY'), true)),
            'nonce' => base64_encode($nonce),
            'seed' => $seed,
        );

        $data = array(
            "auth" => $auth,
        );

        $result = Funciones::post(env('URL_EVERTEC'), 'redirection/api/session/' . $model_order->requestId, $data);

        if (isset($result)) {
            $model_order->status = $result->status->status;
            $model_order->message = $result->status->message;
            $model_order->save();
        }
        return $model_order;

        return view('orders_customer.confirmation')
            ->with('order', $this->order->create($request->all()));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
