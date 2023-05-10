<?php

namespace App\Http\Resources\ServiceRequest;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestResource extends JsonResource
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
            'technician_id'=>$this->technician_id,
            'engineer_id'=>$this->engineer_id,
            'service_type_id'=>$this->service_type_id,
            'generator_info_id'=>$this->generator_info_id,
            'technician_name'=>isset($this->technician) ? $this->technician->name : '',
            'engineer_name'=>isset($this->engineer) ? $this->engineer->name : '',
            'service_type_name'=>isset($this->serviceType) ? $this->serviceType->name : '',
            'generator_info'=>$this->generator_info,
            'site_name'=>$this->site_name,
            'location'=>$this->location,
            'contact_person_name'=>$this->contact_person_name,
            'contact_number'=>$this->contact_number,
            'problem_summary'=>$this->problem_summary,
            'information'=>$this->information,
            'service_created_date'=>$this->service_created_date,
            'service_started_date'=>$this->service_started_date,
            'site_arrived_date'=>$this->site_arrived_date,
            'service_completed_date'=>$this->service_completed_date,
            'service_request_no'=>$this->service_request_no,
            'service_request_status'=>$this->service_request_status,
            'comments'=>$this->comments,
            'initial_remarks'=>$this->initial_remarks,
            'final_remarks'=>$this->final_remarks,
            'initial_issues'=>$this->initial_issues,
            'final_issues'=>$this->final_issues,
            'troubleshooting_brief'=>$this->troubleshooting_brief,
            'problem_section'=>$this->problem_Section,
            'additional_generator_info'=>$this->additionalGeneratorInfo,
            'generatorInfoDetails'=>$this->generatorInfoDetails,
            'service_request_images'=>$this->service_request_images,
            'additionalGeneratorInfo'=>$this->additionalGeneratorInfo,
            'question_answer'=>$this->question_answer,
            'customer_id'=>$this->customer_id,
            'generator_model'=>$this->generator_model,
            'generator_rating'=>$this->generator_rating,
            'customer_name'=>$this->customer_name,
            'customer_mobile'=>$this->customer_mobile,
        ];
    }
}
