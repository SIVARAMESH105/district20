<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class EventCreateRequest
 * namespace App\Http\Requests
 * @package Illuminate\Foundation\Http\FormRequest
 */
class EventCreateRequest extends FormRequest {
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
        return [
                'event_title' => 'required',
                'event_location' => 'required',
                'event_start_date_val' => 'required',
                'event_end_date_val'=>'required',
                'event_description'=>'required',
                'chapter'=>'required'
            ];
    }
    public function messages() {
        return [
            'event_title.required' => "Please enter a event title",
            'event_location.required' => "Please enter a event location",            
            'event_start_date_val.required' => "Please select a event start date",
            'event_end_date_val.required' => "Please select a event end date",
            'event_description.required' => "Please enter a event description",
            'chapter.required' =>  "Please select a chapter",
        ];
    }
}