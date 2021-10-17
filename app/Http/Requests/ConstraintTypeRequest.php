<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConstraintTypeRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|max:5',
            'is_work' => 'required',
            'is_single_day' => 'required',
            'is_group_constraint' => 'required',
            'is_day_in_schedule' => 'required',
        ];
    }
}
