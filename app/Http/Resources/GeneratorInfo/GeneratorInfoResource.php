<?php

namespace App\Http\Resources\GeneratorInfo;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneratorInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'generator_name'=>$this->generator_name,
            'unique_generator_code'=>$this->unique_generator_code,
            'generator_model'=>$this->generator_model,
            'brand_id'=>$this->brand_id,
            'brand_name'=>$this->brand->name,
            'rating'=>$this->rating,
            'generator_serial'=>$this->generator_serial,
            'engine_brand'=>$this->engine_brand,
            'engine_serial'=>$this->engine_serial,
            'alternator_brand'=>$this->alternator_brand,
            'alternator_serial'=>$this->alternator_serial,
            'delivery_status'=>$this->delivery_status,
        ];
    }
}
