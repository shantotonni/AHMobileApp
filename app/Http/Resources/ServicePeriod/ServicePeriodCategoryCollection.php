<?php

namespace App\Http\Resources\ServicePeriod;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServicePeriodCategoryCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($service_period){
                return [
                    'id' => $service_period->id,
                    'name' => $service_period->name,
                    'service_period_category_id' => isset($service_period->service_period_category_details_value) ? $service_period->service_period_category_details_value->service_period_category_id : null,
                    'service_period_category_details_id' => isset($service_period->service_period_category_details_value) ? $service_period->service_period_category_details_value->id : null,
                    'service_request_id' => isset($service_period->service_period_category_details_value) ? $service_period->service_period_category_details_value->service_request_id : null,
                    'current_phase_one' => isset($service_period->service_period_category_details_value) ? $service_period->service_period_category_details_value->current_phase_one : null,
                    'current_phase_two' => isset($service_period->service_period_category_details_value) ? $service_period->service_period_category_details_value->current_phase_two : null,
                    'current_phase_three' => isset($service_period->service_period_category_details_value) ? $service_period->service_period_category_details_value->current_phase_three : null,
                    'rpm' => isset($service_period->service_period_category_details_value) ? $service_period->service_period_category_details_value->rpm : null,
                    'frequency' => isset($service_period->service_period_category_details_value) ? $service_period->service_period_category_details_value->frequency : null,
                    'oil_pressure' => isset($service_period->service_period_category_details_value) ? $service_period->service_period_category_details_value->oil_pressure : null,
                    'cooling_water_temp' => isset($service_period->service_period_category_details_value) ? $service_period->service_period_category_details_value->cooling_water_temp : null,
                ];
            })
        ];
    }
}
