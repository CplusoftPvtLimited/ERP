<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateManufacturerRequest extends FormRequest
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
            'manuName'=> 'required|max:255'
        ];
    }
    public function messages()
    {

        return [
            'manuName.required'            =>  __('Manufacturer Name is required.'),
            'manuName.max'              =>  __('Manufacturer Name must be less than 255 characters.'),
        ];
    }
}
