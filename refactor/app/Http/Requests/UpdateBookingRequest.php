<?php

namespace DTApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateBookingRequest extends FormRequest {

    public function authorize(): bool {
        return Auth::user()->user_type == env('CUSTOMER_ROLE_ID');
    }

    public function rules(): array {
        $rules = [
            'from_language_id' => 'required',
            'duration' => 'required',
            'job_for' => '',
            'immediate' => '',
            'customer_physical_type' => '',

            /*
             * OTHER REQUEST FIELDS THAT NEEDS VALIDATION CAN PUT HERE
             * */

        ];
        if($this->immediate == 'no') {
            $rules['due_date'] = 'nullable|date';
            $rules['due_time'] = 'nullable|date';
            if(!$this->customer_phone_type && $this->customer_physical_type) {
                $rules['customer_phone_type'] = 'required';
                $rules['customer_physical_type'] = 'required';
            }
        }

        /*
         * OTHER CONDITIONAL VALIDATION CODE CAN ALSO PUT HERE
         * */

        return $rules;
    }

//    public function messages(): array {
//        return [
//            'from_language_id.required' => 'Du måste fylla in alla fält',
//            'due_date.date'             => '"Du måste fylla in alla fält"',
//            'due_time.date'             => '"Du måste fylla in alla fält"',
//            'customer_phone_type.required' => 'Du måste fylla in alla fält',
//            'customer_physical_type.required' => 'Du måste fylla in alla fält',
//        ];
//    }
}