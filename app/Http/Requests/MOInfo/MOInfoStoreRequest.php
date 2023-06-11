<?php

namespace App\Http\Requests\MOInfo;

use Illuminate\Foundation\Http\FormRequest;

class MOInfoStoreRequest extends FormRequest
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
           'Name'=>'required|min:3',
           'Email'=>'required|min:3',
           'Mobile'=>'required|min:3',
           'Address'=>'required|min:3',
           'MOImage'=>'required',

        ];
    }
}
