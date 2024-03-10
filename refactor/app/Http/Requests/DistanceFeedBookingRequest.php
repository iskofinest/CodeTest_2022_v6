<?php

namespace DTApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DistanceFeedBookingRequest extends FormRequest {

    public function authorize(): bool {
        return Auth::check();
    }

    public function rules(): array {
        return [
            'distance' => 'nullable',
            'time' => 'nullable',
            'jobid' => 'nullable',
            'session_time' => 'nullable',
            'flagged' => 'nullable',
            'admincomment' => 'required_if:flagged,true',
            'manually_handled' => 'nullable',
            'by_admin' => 'nullable',
        ];
    }

    protected function prepareForValidation() {
        $flagged = $this->input('flagged');
        $manuallyHandled = $this->input('manually_handled');
        $admin = $this->input('by_admin');
        if ($flagged === 'true') {
            $this->merge([ 'flagged' => 'yes', ]);

            $this->merge([
                'admincomment' => 'required',
            ]);
        } else {
            $this->merge([ 'flagged' => 'no', ]);
        }
        $this->merge([
            'manually_handled' => $manuallyHandled === 'true' ? 'yes' : 'no',
            'by_admin' => $admin === 'true' ? 'yes' : 'no',
        ]);
    }
}