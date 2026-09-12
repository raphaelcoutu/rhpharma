<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuildStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scheduleId' => ['required', 'integer', 'exists:schedules,id'],
            'buildStep' => ['required', 'string', Rule::in(['weekends', 'last_evening', 'clinical'])],
            'status' => ['required', 'integer', 'between:0,6'],
        ];
    }
}
