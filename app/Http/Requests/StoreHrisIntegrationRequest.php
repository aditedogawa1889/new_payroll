<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHrisIntegrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermissionId(0);
    }

    public function rules(): array
    {
        return [
            'id_integ_type' => ['required', 'exists:master_integration_types,id_integ_type'],
            'emp_number' => ['required', 'numeric'],
            'employee_id' => ['required', 'string'],
            'employee_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'job_title_name' => ['nullable', 'string', 'max:255'],
            'job_title_future_name' => ['nullable', 'string', 'max:255'],
            'job_title_effective_date' => ['nullable', 'date', 'after_or_equal:join_date'],
            'job_level' => ['nullable', 'string', 'max:255'],
            'join_date' => ['nullable', 'date'],
            'location_from_name' => ['nullable', 'string', 'max:255'],
            'location_to_name' => ['nullable', 'string', 'max:255'],
            'effective_year' => ['nullable', 'string', 'size:4'],
            'termination_date' => ['nullable', 'date'],
            'last_payroll_date' => ['nullable', 'date'],
            'npwp' => ['nullable', 'string', 'max:255'],
            'bpjs_kesehatan' => ['nullable', 'string', 'max:255'],
            'bpjs_ketenagakerjaan' => ['nullable', 'string', 'max:255'],
            'ktp' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:50'],
            'bank_account' => ['nullable', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
