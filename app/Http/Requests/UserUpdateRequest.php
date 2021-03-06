<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserUpdateRequest
 * namespace App\Http\Requests
 * @package Illuminate\Foundation\Http\FormRequest
 */
class UserUpdateRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */    
    public function rules() {
    	$id = $this->request->get('id');
        return [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$id,
                'chapter' => 'required',
                //'rnumber' => 'required',
                'user_role'=>'required'
            ];
    }

    public function messages() {
        return [
            'name.required' => 'Please Enter Name.',
            'email.required' => 'Please Enter Email.',
            'email.unique' => 'Email Already Registerd.',
            'email.email' => 'Please Enter Valid Email.',
            'chapter.required' => 'Please Select Chapter.',
            'user_role.required' => 'Please Select User Role.',
            'rnumber.required' => 'Please Enter Registration Number.',
        ];
    }
}