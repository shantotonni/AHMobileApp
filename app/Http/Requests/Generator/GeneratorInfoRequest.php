<?php

namespace App\Http\Requests\Generator;

use Illuminate\Foundation\Http\FormRequest;

class GeneratorInfoRequest extends FormRequest
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
            'unique_generator_code'=>'required',
            'generator_model'=>'required',
            'brand_id'=>'required',
            'rating'=>'required',
            'generator_serial'=>'required',
            'engine_brand'=>'required',
            'engine_serial'=>'required',
            'alternator_brand'=>'required',
            'alternator_serial'=>'required',
            //'generator_image'=>'required',
        ];
    }
}
