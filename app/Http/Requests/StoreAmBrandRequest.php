<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAmBrandRequest extends FormRequest
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
            'brandName'=> 'required|unique:ambrand,brandName|max:255',
            'lang'=> 'required',
            'articleCountry'=> 'required',

            
        ];
    }
    public function messages()
    {

        return [
            'brandName.required'            =>  __('Brand Name is required.'),
            'brandName.unique'              =>  __('This Brand Name already exist.'),
            'brandName.max'              =>  __('Brand Name must be less than 255 characters.'),
            'lang.required'            =>  __('Language is required.'),
            'articleCountry.required'            =>  __('Artcle Country is required.'),


        ];
    }
}
