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
            'job_title_name' => ['nullable', 'string', 'max:255'],
            'job_title_future_name' => ['nullable', 'string', 'max:255'],
            'job_title_effective_date' => ['nullable', 'date'],
            'join_date' => ['nullable', 'date'],
            'location_from_name' => ['nullable', 'string', 'max:255'],
            'location_to_name' => ['nullable', 'string', 'max:255'],
            'effective_year' => ['nullable', 'string', 'size:4'],
            'termination_date' => ['nullable', 'date'],
            'last_payroll_date' => ['nullable', 'date'],
        ];
    }
}
