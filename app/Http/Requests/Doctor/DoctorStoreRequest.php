<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class DoctorStoreRequest extends FormRequest
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
          'DoctorName'=>'required|min:3',
          'Designation'=>'required|min:3',
          'Mobile'=>'required|min:10',
          'Email'=>'required|min:3',
          'Details'=>'required|min:3',
          'DoctorImage'=>'required',
          'AddressOne'=>'required|min:3',
          'DistrictID'=>'required',
          'UpazilaID'=>'required',
        ];
    }
}
