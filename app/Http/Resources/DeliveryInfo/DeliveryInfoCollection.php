<?php

namespace App\Http\Resources\DeliveryInfo;

use App\Http\Resources\GeneratorInfo\GeneratorInfoResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DeliveryInfoCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($delivery_info){
                return [
                    'id'=>$delivery_info->id,
                    'generator_info_id'=>$delivery_info->generator_info_id,
                    'unique_generator_code'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->unique_generator_code:'',
                    'engineer_id'=>$delivery_info->engineer_id,
                    'engineer_name'=>isset($delivery_info->engineer) ? $delivery_info->engineer->name : '',
                    'technician_id'=>$delivery_info->technician_id,
                    'technician_name'=> isset($delivery_info->technician) ? $delivery_info->technician->name : '',
                    'client_name'=>$delivery_info->client_name,
                    'delivery_location'=>$delivery_info->delivery_location,
                    'truck_number'=>$delivery_info->truck_number,
                    'driver_name'=>$delivery_info->driver_name,
                    'driver_number'=>$delivery_info->driver_number,
                    'delivery_create_date'=>$delivery_info->delivery_create_date,
                    'delivery_assign_date'=>$delivery_info->delivery_assign_date,
                    'delivery_complete_date'=>$delivery_info->delivery_complete_date,
                    'generator_info'=>$delivery_info->generator_info,
                    'site_name'=>$delivery_info->site_name,
                    'location'=>$delivery_info->location,
                    'contact_person_name'=>$delivery_info->contact_person_name,
                    'contact_number'=>$delivery_info->contact_number,
                    'order'=>$delivery_info->order,
                    'delivery_status'=>$delivery_info->delivery_status,
                    //'GeneratorInfo'=> new GeneratorInfoResource($delivery_info->generatorInfo),

                    'generator_name'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->generator_name:'',
                    'generator_model'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->generator_model:'',
                    'brand_id'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->brand_id:'',
                    'brand_name'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->brand->name:'',
                    'rating'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->rating:'',
                    'generator_serial'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->generator_serial:'',
                    'engine_brand'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->engine_brand:'',
                    'engine_serial'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->engine_serial:'',
                    'alternator_brand'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->alternator_brand:'',
                    'alternator_serial'=>isset($delivery_info->generatorInfo) ? $delivery_info->generatorInfo->alternator_serial:'',
                ];
            })
        ];
    }
}
