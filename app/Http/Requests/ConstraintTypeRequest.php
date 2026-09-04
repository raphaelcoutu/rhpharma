<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConstraintTypeRequest extends FormRequest
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
            'azure_id' => ['nullable', 'integer'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'code' => ['required', 'string', 'max:5'],
            'is_work' => ['required', 'boolean'],
            'is_single_day' => ['required', 'boolean'],
            'is_group_constraint' => ['required', 'boolean'],
            'is_day_in_schedule' => ['required', 'boolean'],
        ];
    }
}
