<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'firstname' => 'required|alpha_dash',
            'lastname' => 'required|alpha_dash',
            'email' => 'required|email|unique:users,email,'.$this->get('id'),
            'workdays_per_week' => 'required|digits:1',
            'seniority' => 'required',
            'is_active' => 'required'
        ];
    }
}
