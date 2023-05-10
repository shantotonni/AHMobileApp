<?php

namespace App\Http\Resources\Technician;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TechnicianCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($technician){
                return [
                    'id'=>$technician->id,
                    'name'=>$technician->name,
                    'UserID'=>$technician->UserID,
                    'role_id'=>$technician->role_id,
                    'role_name'=>isset($technician->role) ? $technician->role->name : '',
                    'designation'=>$technician->designation,
                    'Mobile'=>$technician->mobile,
                    'email'=>$technician->email,
                    'is_active'=>$technician->is_active,
                    'device_token'=>$technician->device_token,
                ];
            })
        ];
    }
}
