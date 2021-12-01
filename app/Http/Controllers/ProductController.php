<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Events\ProductShipped;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\ProductShipped as ShippedEmail;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function store (Request $request) {
        $product = Product::create($request->all());
        return $product;
    }

    public function show (Product $product) {
        return 'id: ' . $product->id;
    }

    public function index() {
        return Product::all();
    }

    public function shipped ($product) {
       ProductShipped::dispatch($product);
       
       if ($product->price > 60) {
            $this->sendEmailReminder($product);
       }
    }

 

    private function sendEmailReminder(Product $product)
    {   
       Mail::to('robert@thankview.com')->send(new ShippedEmail($product));
    }
}
