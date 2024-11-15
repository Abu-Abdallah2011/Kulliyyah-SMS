<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisciplinaryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255'
            ],
            'description' => [
                'required',
                'string'
            ],
            'action_taken' => [
                'required',
                'string'
            ],
            'status' => [
                'string'
            ],
            'school_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5000'
            ],
            'offender_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5000'
            ],
            'student_id' => [
                'nullable',
                'exists:students_details_tables,id'
            ],
            'teacher_id' => [
                'nullable',
                'exists:teachers_details,id'
            ],
        
        ];
    }
}
