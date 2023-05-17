<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function productDetails(Request $request){
        $product = Product::where('ID',$request->ProductId)->first();
        return response()->json([
           'product' =>$product
        ]);
    }
}
