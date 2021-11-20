<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class NotificationCreateRequest
 * namespace App\Http\Requests
 * @package Illuminate\Foundation\Http\FormRequest
 */
class NotificationCreateRequest extends FormRequest
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
                'title' => 'required',
                'desc' => 'required',
                'link' => 'required'
            ];
    }

    public function messages() {
        return [
            'title.required' => 'Please Enter Title.',
            'desc.required' => 'Please Enter Description.',
            'link.required' => 'Please Enter Link.'
        ];
    }
}
