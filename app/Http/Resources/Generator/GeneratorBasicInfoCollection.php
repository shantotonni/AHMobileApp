<?php

namespace App\Http\Resources\Generator;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GeneratorBasicInfoCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($generator_basic){
                return [
                    'id'=>$generator_basic->id,
                    'generator_name'=>$generator_basic->generator_name,
                    'generator_brand'=>$generator_basic->generator_brand,
                    'generator_model'=>$generator_basic->generator_model,
                    'country_of_origin'=>$generator_basic->country_of_origin,
                    'enginee_and_alternator'=>$generator_basic->enginee_and_alternator,
                    'generator_feature'=>$generator_basic->generator_feature,
                    'image'=>url('/').'/generator/'.$generator_basic->generator_image,
                ];
            })
        ];
    }
}
