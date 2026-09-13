<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShiftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:10'],
            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')->where('branch_id', $this->user()->branch_id),
            ],
            'shift_type_id' => [
                'required',
                'integer',
                Rule::exists('shift_types', 'id')->where('branch_id', $this->user()->branch_id),
            ],
        ];
    }
}
