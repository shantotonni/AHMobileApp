<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($category){
                return [
                    'ID'=>$category->ID,
                    'CategoryName'=>$category->CategoryName,
                    'PortfolioID'=>$category->PortfolioID,
                    'PortfolioName'=>isset($category->portfolio) ? $category->portfolio->PortfolioName: '',
                    'Details'=>$category->Details,
                    'Active'=>$category->Active,
                    'CategoryImage'=>$category->CategoryImage,
                    'CategoryImageMobile'=>url('/').'/images/category/'.$category->CategoryImage,
                ];
            })
        ];
    }
}
