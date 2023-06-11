<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
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
            'ShopName' => $this->ShopName,
            'ShopOwnerName' => $this->ShopOwnerName,
            'ShopOwnerMobile'=>$this->ShopOwnerMobile,
            'ShopAddress' => $this->ShopAddress,
            'DistrictID' => $this->DistrictID,
            'ShopImage'=>$this->ShopImage,
            'ShopImageMobile'=>url('/').'/images/shop/'.$this->ShopImage,
            'DistrictName'=>isset($this->district) ? $this->district->DistrictName: '',
            'UpazilaID' => $this->UpazilaID,
            'UpazilaName' => isset($this->upazila) ? $this->upazila->UpazilaName:'',
        ];
    }
}
