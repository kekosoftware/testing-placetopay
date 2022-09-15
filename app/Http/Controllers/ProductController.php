<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
        $buy = Product::find($id);
        return view('checkout', compact('buy'));
    }
}
