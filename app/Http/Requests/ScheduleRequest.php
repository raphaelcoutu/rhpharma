<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
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
            'constraint_limit_date' => 'required|date|after:today',
            'start_date' => 'required|date|after:constraint_limit_date|day:sunday',
            'end_date' => 'required|date|after:start_date|unique_interval:start_date|day:saturday'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom de l\'horaire est requis.',
            'constraint_limit_date.required' => 'La date de limite pour les contraintes est requise.',
            'start_date.required' => 'La date de début est requise.',
            'end_date.required' => 'Le date de fin est requise.',
        ];
    }
}
