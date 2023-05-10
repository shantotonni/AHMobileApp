<?php

namespace App\Http\Requests\SparesInquiry;

use Illuminate\Foundation\Http\FormRequest;

class SparesInquiryRequest extends FormRequest
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
            'customer_name'=>'required',
            'location'=>'required',
            'contact_details'=>'required',
            'required_spares'=>'required',
        ];
    }
}
