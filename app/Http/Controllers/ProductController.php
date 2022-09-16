<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\PaymentTrait;

class ProductController extends Controller
{
    use PaymentTrait;
    /**
     * Product list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$products = Product::latest()->paginate(10);
        return view('welcome', compact('products'));
    }

    public function formCheckout($id)
    {
        $product = Product::find($id);

        return view('checkout', compact('product'));
    }
}
