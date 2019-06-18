<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class imagesRequest extends FormRequest
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
            'fileUpload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10485760',
        ];
    }


    public function messages()
    {
        return [
            'fileUpload.required'    => 'A title is required',
            'fileUpload.mimes'    => 'File type not supported - ',
            'fileUpload.max'    => 'Maximum is 10MB',
        ];
    }
}
