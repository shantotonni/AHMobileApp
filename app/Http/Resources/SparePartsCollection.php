<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SparePartsCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($parts){
                return [
                    'id'=>$parts->id,
                    'parts_name'=>$parts->parts_name,
                    'parts_no'=>$parts->parts_no,
                    'trade_price'=>$parts->trade_price,
                    'image'=>$parts->image,
                ];
            })
        ];
    }
}
