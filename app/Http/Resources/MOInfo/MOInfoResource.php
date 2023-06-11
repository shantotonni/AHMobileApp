<?php

namespace App\Http\Resources\MOInfo;

use Illuminate\Http\Resources\Json\JsonResource;

class MOInfoResource extends JsonResource
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
            'ID'=>$this->ID,
            'Name'=>$this->Name,
            'Email'=>$this->Email,
            'Mobile'=>$this->Mobile,
            'Address'=>$this->Address,
            'MOImage'=>$this->MOImage,
            'MOImageMobile'=>url('/').'/images/MOinfo/'.$this->MOImage,
            'DistrictID' => $this->DistrictID,
            'DistrictName'=>isset($this->district) ? $this->district->DistrictName: '',
            'UpazilaID' => $this->UpazilaID,
            'UpazilaName' => isset($this->upazila) ? $this->upazila->UpazilaName:'',
        ];
    }
}
