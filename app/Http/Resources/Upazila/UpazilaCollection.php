<?php

namespace App\Http\Resources\Upazila;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UpazilaCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($upazila) {
                return [
                    'ID' => $upazila->ID,
                    'DistrictId' => $upazila->DistrictId,
                    'DistrictName'=>isset($upazila->district) ? $upazila->district->DistrictName: '',
                    'Code' => $upazila->Code,
                    'UpazilaName' => $upazila->UpazilaName,
                    'UpazilaNameBn' => $upazila->UpazilaNameBn
                ];
            })
        ];
    }
}
