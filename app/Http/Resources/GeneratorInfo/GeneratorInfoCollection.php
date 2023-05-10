<?php

namespace App\Http\Resources\GeneratorInfo;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GeneratorInfoCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($generator_info){
                return [
                    'id'=>$generator_info->id,
                    'generator_name'=>$generator_info->generator_name,
                    'unique_generator_code'=>$generator_info->unique_generator_code,
                    'generator_model'=>$generator_info->generator_model,
                    'brand_id'=>$generator_info->brand_id,
                    'brand_name'=>$generator_info->brand->name,
                    'rating'=>$generator_info->rating,
                    'generator_serial'=>$generator_info->generator_serial,
                    'engine_brand'=>$generator_info->engine_brand,
                    'engine_serial'=>$generator_info->engine_serial,
                    'alternator_brand'=>$generator_info->alternator_brand,
                    'alternator_serial'=>$generator_info->alternator_serial,
                    'delivery_status'=>$generator_info->delivery_status,
                    'generator_info_image'=>$generator_info->generator_info_image,
                ];
            })
        ];
    }
}
