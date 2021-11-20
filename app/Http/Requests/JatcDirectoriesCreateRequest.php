<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class JatcDirectoriesCreateRequest
 * namespace App\Http\Requests
 * @package Illuminate\Foundation\Http\FormRequest
 */
class JatcDirectoriesCreateRequest extends FormRequest
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
                // 'name' => 'required',
                'email' => 'required|email',
                'chapter' => 'required',
                'position' => 'required',
                'phone' => 'required',
                //'phone' => 'required|numeric',
            ];
    }

    public function messages() {
        return [
            'name.required' => 'Please Enter Name.',
            'email.required' => 'Please Enter Email.',
            'email.email' => 'Please Enter Valid Email.',
            'chapter.required' => 'Please Select Chapter.',
            'position.required' => 'Please Enter Position',
            'phone.required' => 'Please Enter Phone.',
        ];
    }
}
