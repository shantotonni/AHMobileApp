<?php

namespace App\Http\Resources\ServiceType;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceTypeCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($service_type){
                return [
                    'id'=>$service_type->id,
                    'name'=>$service_type->name,
                    'name_bn'=>$service_type->name_bn,
                    'code'=>$service_type->code,
                    'created_at'=>$service_type->created_at,
                ];
            })
        ];
    }
}
