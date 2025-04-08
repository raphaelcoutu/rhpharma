<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'branch_id' => \Auth::user()->branch->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('departments', 'name')
                    ->ignore($this->id)
                    ->where('workplace_id', $this->workplace_id)
                    ->where('branch_id', $this->branch_id)
            ],
            'workplace_id' => 'required',
            'department_type_id' => 'required'
        ];
    }
}
