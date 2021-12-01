<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductImageUploaderController extends Controller
{
    public function __invoke(Request $request) {
        $request->file('image')->storeAs('', $request->fileName);     
    }
}
