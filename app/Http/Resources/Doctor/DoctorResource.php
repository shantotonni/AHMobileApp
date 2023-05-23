<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'ID' => $this->ID,
            'DoctorName' => $this->DoctorName,
            'Designation' => $this->Designation,
            'Mobile'=>$this->Mobile,
            'Email'=>$this->Email,
            'Details' => $this->Details,
            'DoctorImage'=>$this->DoctorImage,
            'DoctorImageMobile'=>url('/').'/images/doctor/'.$this->DoctorImage,
            'AddressOne' => $this->AddressOne,
            'AddressTwo' => $this->AddressTwo,
            'District' => $this->District,
            'DistrictName'=>isset($this->district) ? $this->district->DistrictName: '',
            'Upazila' => $this->Upazila,
            'UpazilaName' => isset($this->upazila) ? $this->upazila->UpazilaName:'',

        ];
    }
}
