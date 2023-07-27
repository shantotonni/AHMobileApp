<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'ID' => $this->ID,
            'ProductName' => $this->ProductName,
            'ProductAttribute' => $this->ProductAttribute,
            'Details' => strip_tags($this->Details),
            'ProductImage' => $this->ProductImage,
            'PortfolioID'=>$this->PortfolioID,
            'PortfolioName'=>isset($this->portfolio) ? $this->portfolio->PortfolioName: '',
            'CategoryID'=>$this->CategoryID,
            'CategoryName'=>isset($this->category) ? $this->category->CategoryName: '',
            'ProductImageMobile'=>url('/').'/images/product/'.$this->ProductImage,
        ];
    }
}
