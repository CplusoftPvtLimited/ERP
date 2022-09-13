<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreManufacturerRequest extends FormRequest
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
            'manuName'=> 'required|unique:manufacturers,manuName|max:255',
            'linkingTargetType'=> 'required',
        ];
    }
    public function messages()
    {

        return [
            'manuName.required'            =>  __('Manufacturer Name is required.'),
            'manuName.unique'              =>  __('This Manufacturer Name already exist.'),
            'manuName.max'              =>  __('Manufacturer Name must be less than 255 characters.'),
            'linkingTargetType.required'            =>  __('Linkage Target Type is required.'),

        ];
    }
}
