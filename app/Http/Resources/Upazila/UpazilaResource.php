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
        'ID' => $this->ID,
        'DistrictID' => $this->DistrictId,
        'DistrictName'=>isset($this->district) ? $this->district->DistrictName: '',
        'Code' => $this->Code,
        'UpazilaName' => $this->UpazilaName,
        'UpazilaNameBn' => $this->UpazilaNameBn
    ];
    }
}
