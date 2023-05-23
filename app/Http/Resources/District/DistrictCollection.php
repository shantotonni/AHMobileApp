<?php

namespace App\Http\Resources\District;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DistrictCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($district) {
                return [
                    'ID' => $district->ID,
                    'DistrictName' => $district->DistrictName,
                    'DistrictNameBn' => $district->DistrictNameBn
                ];
            })
        ];
    }
}
