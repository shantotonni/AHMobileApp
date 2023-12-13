<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Category;
use App\Models\Portfolio;
use App\Models\Product;
use Intervention\Image\Facades\Image;
use UConverter;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category','portfolio')->orderby('ID', 'desc')->with('category')->paginate(15);
        return new ProductCollection($products);
    }

    public function store(ProductStoreRequest $request)
    {
        if ($request->ProductImage) {
            $ProductImage = $request->ProductImage;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($ProductImage, 0, strpos($ProductImage, ';')))[1])[1];
            Image::make($ProductImage)->save(public_path('images/product/') . $name);
        } else {
            $name = 'not_found_jpg';
        }

        $product = new Product();
        $product->ProductName = $request->ProductName;
        $product->ProductAttribute = $request->ProductAttribute;
        $product->ProductImage = $name;
        $product->Details = $request->Details;
        $product->PortfolioID = $request->PortfolioID;
        $product->CategoryID = $request->CategoryID;
        $product->save();
        return response()->json(['message' => ' Product created successfully', 200]);

    }
    public function edit($id){
        $PortfolioID = Portfolio::where('PortfolioID', $id)->get();
        return new ProductResource($PortfolioID);
    }

    public function update(ProductStoreRequest $request, $id)
    {
        $product = Product::where('ID', $id)->first();
        $ProductImage = $request->ProductImage;
        if ($ProductImage != $product->ProductImage) {
            if ($request->ProductImage) {
                //code for remove old file
                if ($product->ProductImage != '' && $product->ProductImage != null) {
                    $destinationPath = 'images/product/';
                    $file_old = public_path('/').$destinationPath . $product->ProductImage;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($ProductImage, 0, strpos($ProductImage, ';')))[1])[1];
                Image::make($ProductImage)->save(public_path('images/product/') . $name);
            } else {
                $name = $product->ProductImage;
            }
        } else {
            $name = $product->ProductImage;
        }

        $product->ProductName = $request->ProductName;
        $product->ProductAttribute = $request->ProductAttribute;
        $product->ProductImage = $name;
        $product->Details = $request->Details;
        $product->PortfolioID = $request->PortfolioID;
        $product->CategoryID = $request->CategoryID;
        $product->save();
        return response()->json([
            'message' => ' Product uploaded successfully', 200
        ]);
    }

    public function destroy($id)
    {
        $product = Product::where('ID', $id)->first();
        if ($product->ProductImage) {
            $destinationPath = 'images/product/';

            $file = public_path('/') . $destinationPath . $product->ProductImage;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $product->delete();
        return response()->json(['message' => 'Product Deleted Successfully'], 200);
    }

    public function search($query)
    {
        return new ProductCollection(Product::where('ProductName', 'LIKE', "%$query%")->latest()->paginate(20));
    }

}
