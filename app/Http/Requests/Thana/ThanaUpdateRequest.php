<?php

namespace App\Http\Requests\Thana;

use Illuminate\Foundation\Http\FormRequest;

class ThanaUpdateRequest extends FormRequest
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
            'ThanaName'=>'required|min:3'
        ];
    }
}
