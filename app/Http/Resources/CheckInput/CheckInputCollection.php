<?php

namespace App\Http\Resources\CheckInput;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CheckInputCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($check_input){
                return [
                    'id' => $check_input->id,
                    'name' => $check_input->name,
                    'image' => url('/').'/checking_input/'.$check_input->image,
                    'checking_input_id' => isset($check_input->checkingValue) ? $check_input->checkingValue->checking_input_id : null,
                    'checking_input_value_id' => isset($check_input->checkingValue) ? $check_input->checkingValue->id : null,
                    'service_request_id' => isset($check_input->checkingValue) ? $check_input->checkingValue->service_request_id : null,
                    'value' => isset($check_input->checkingValue) ? $check_input->checkingValue->value : null,
                ];
            })
        ];
    }
}
