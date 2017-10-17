<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConstraintRequest extends FormRequest
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
            'constraint_type_id' => 'required',
            'start_datetime' => 'required|date|after:today',
            'end_datetime' => 'required|date|after:start_datetime',
            'weight' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'constraint_type_id.required' => 'Le champ Type de contrainte est obligatoire.',
            'start_datetime.after' => 'Le champ :attribute doit être une date postérieure à aujourd\'hui.'
        ];
    }
}
