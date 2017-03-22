<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->request->add(['branch_id' => \Auth::user()->branch->id]);

        return [
            'name' => 'required|unique:departments,name,' . $this->id . ',id,workplace_id,' . $this->workplace_id 
                    . ',branch_id,' . $this->branch_id,
            'code' => 'required|unique:departments,code,' . $this->id . ',id,branch_id,' . $this->branch_id
        ];
    }
}
