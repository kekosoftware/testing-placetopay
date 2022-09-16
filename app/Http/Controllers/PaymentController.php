<?php

namespace App\Http\Controllers;

use App\Traits\PaymentTrait;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use PaymentTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payment');
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
    public function store(Request $request)
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
        //
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
