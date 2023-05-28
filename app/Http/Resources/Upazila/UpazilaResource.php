<?php

namespace App\Http\Resources\Upazila;

use Illuminate\Http\Resources\Json\JsonResource;

class UpazilaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {    return [
        'ID' => $upazila->ID,
        'DistrictId' => $upazila->DistrictId,
        'DistrictName'=>isset($doctor->district) ? $doctor->district->DistrictName: '',
        'Code' => $upazila->Code,
        'UpazilaName' => $upazila->UpazilaName,
        'UpazilaNameBn' => $upazila->UpazilaNameBn
    ];
    }
}
