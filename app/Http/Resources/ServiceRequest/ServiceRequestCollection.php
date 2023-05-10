<?php

namespace App\Http\Resources\ServiceRequest;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceRequestCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($service){
                return [
                    'id'=>$service->id,
                    'technician_id'=>$service->technician_id,
                    'problem_section_id'=>$service->problem_section_id,
                    'problem_section_name'=>isset($service->problem_section) ? $service->problem_section->name : '',
                    'engineer_id'=>$service->engineer_id,
                    'service_type_id'=>$service->service_type_id,
                    'technician_name'=>isset($service->technician) ? $service->technician->name : '',
                    'engineer_name'=>isset($service->engineer) ? $service->engineer->name : '',
                    'service_type_name'=>isset($service->serviceType) ? $service->serviceType->name : '',
                    'generator_info'=>$service->generator_info,
                    'site_name'=>$service->site_name,
                    'location'=>$service->location,
                    'contact_person_name'=>$service->contact_person_name,
                    'contact_number'=>$service->contact_number,
                    'problem_summary'=>$service->problem_summary,
                    'information'=>$service->information,
                    'service_created_date'=>$service->service_created_date,
                    'site_arrived_date'=>$service->site_arrived_date,
                    'service_started_date'=>$service->service_started_date,
                    'service_completed_date'=>$service->service_completed_date,
                    'service_request_no'=>$service->service_request_no,
                    'service_request_status'=>$service->service_request_status,
                    'comments'=>$service->comments,
                    'initial_remarks'=>$service->initial_remarks,
                    'final_remarks'=>$service->final_remarks,
                    'initial_issues'=>$service->initial_issues,
                    'final_issues'=>$service->final_issues,
                    'customer_id'=>$service->customer_id,
                    'generator_model'=>$service->generator_model,
                    'generator_rating'=>$service->generator_rating,
                    'customer_name'=>$service->customer_name,
                    'customer_mobile'=>$service->customer_mobile,
                ];
            })
        ];
    }
}
