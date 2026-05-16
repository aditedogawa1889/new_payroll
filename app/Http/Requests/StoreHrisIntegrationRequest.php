<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHrisIntegrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_integ_type' => ['required', 'exists:master_integration_types,id_integ_type'],
            'emp_number' => ['required', 'numeric'],
            'employee_id' => ['required', 'string'],
            'job_title_id' => ['required', 'integer'],
            'job_title_future_id' => ['nullable', 'integer'],
            'join_date' => ['required', 'date'],
            'location_from_id' => ['required', 'integer'],
            'location_to_id' => ['required', 'integer'],
            'effective_date' => ['required', 'date', 'after_or_equal:join_date'],
        ];
    }
}
