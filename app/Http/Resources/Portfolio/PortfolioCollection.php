<?php

namespace App\Http\Resources\Portfolio;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PortfolioCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($portfolio){
                return [
                    'ID'=>$portfolio->ID,
                    'PortfolioName'=>$portfolio->PortfolioName,
                    'Active'=>$portfolio->Active,
                    'PortfolioImage'=>$portfolio->PortfolioImage,
                ];
            })
        ];
    }
}
