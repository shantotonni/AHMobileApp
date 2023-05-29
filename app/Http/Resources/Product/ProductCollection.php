<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($product) {
                return [
                    'ID' => $product->ID,
                    'ProductName' => $product->ProductName,
                    'ProductAttribute' => $product->ProductAttribute,
                    'Details' => strip_tags($product->Details),
                    'ProductImage' => $product->ProductImage,
                    'PortfolioID'=>$product->PortfolioID,
                    'PortfolioName'=>isset($product->portfolio) ? $product->portfolio->PortfolioName: '',
                    'CategoryID'=>$product->CategoryID,
                    'CategoryName'=>isset($product->category) ? $product->category->CategoryName: '',
                    'ProductImageMobile'=>url('/').'/images/product/'.$product->ProductImage,
                ];
            })
        ];
    }
}
