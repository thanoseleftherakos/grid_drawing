<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSymbolFormRequest extends FormRequest
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

    public function validationData()
    {
        return $this->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'mimetypes:image/jpeg,image/bmp,image/png',
            // 'symbol_category_id' => 'required'
        ];
    }
    public function attributes()
    {
        return [
            'symbol_category_id' => 'Symbol Category',
        ];
    }

    public function messages()
    {
        return [
            'symbol_category_id.required' => 'A symbol category is required',
        ];
    }
}
