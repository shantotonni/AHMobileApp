<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($doctor) {
                return [
                    'ID' => $doctor->ID,
                    'DoctorName' => $doctor->DoctorName,
                    'Designation' => $doctor->Designation,
                    'Mobile'=>$doctor->Mobile,
                    'Email'=>$doctor->Email,
                    'Details' => $doctor->Details,
                    'DoctorImage'=>$doctor->DoctorImage,
                    'DoctorImageMobile'=>url('/').'/images/doctor/'.$doctor->DoctorImage,
                    'AddressOne' => $doctor->AddressOne,
                    'AddressTwo' => $doctor->AddressTwo,
                    'DistrictID' => $doctor->DistrictID,
                    'DistrictName'=>isset($doctor->district) ? $doctor->district->DistrictName: '',
                    'UpazilaID' => $doctor->UpazilaID,
                    'UpazilaName' => isset($doctor->upazila) ? $doctor->upazila->UpazilaName:'',

                ];
            })
        ];
    }
}
