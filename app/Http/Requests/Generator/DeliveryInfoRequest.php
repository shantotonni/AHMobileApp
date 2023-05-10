<?php

namespace App\Http\Requests\Generator;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'generator_info_id'=>'required',
            'client_name'=>'required',
            'delivery_location'=>'required',
            'truck_number'=>'required',
            'driver_name'=>'required',
            'driver_number'=>'required',
        ];
    }
}
