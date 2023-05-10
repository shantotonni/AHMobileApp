<?php

namespace App\Http\Resources\SparesInquiry;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SparesInquiryCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($spares_inquiry){
                return [
                    'id' => $spares_inquiry->id,
                    'customer_name' => $spares_inquiry->customer_name,
                    'location' => $spares_inquiry->location,
                    'contact_details' => $spares_inquiry->contact_details,
                    'required_spares' => $spares_inquiry->required_spares,
                    'technician_id' => $spares_inquiry->technician_id,
                    'technician_name' => isset($spares_inquiry->technician) ? $spares_inquiry->technician->name : null,
                ];
            })
        ];
    }
}
