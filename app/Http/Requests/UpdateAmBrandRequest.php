<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAmBrandRequest extends FormRequest
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
            'brandName'=> 'required|max:255|unique:ambrand,brandName,'.$this->supplier,
        
        ];
    }
    public function messages()
    {

        return [
            'brandName.required'            =>  __('Brand Name is required.'),
            'brandName.unique'              =>  __('This Brand Name already exist.'),
            'brandName.max'              =>  __('Brand Name must be less than 255 characters.'),
        ];
    }
}
