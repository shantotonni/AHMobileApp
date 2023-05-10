<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($customer){
                return [
                    'id'=>$customer->id,
                    'name'=>$customer->name,
                    'code'=>$customer->code,
                    'mobile'=>$customer->mobile,
                    'date_of_purchase'=>$customer->date_of_purchase,
                    'image'=>$customer->image,
                    'status'=>$customer->status,
                ];
            })
        ];
    }
}
