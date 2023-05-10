<?php

namespace App\Http\Requests\ServiceRequest;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'technician_id'=>'required',
            'service_type_id'=>'required',
            'problem_section_id'=>'required',
            'generator_info'=>'required',
            'site_name'=>'required',
            'location'=>'required',
            'contact_person_name'=>'required',
            'contact_number'=>'required',
            'problem_summary'=>'required',
        ];
    }
}
