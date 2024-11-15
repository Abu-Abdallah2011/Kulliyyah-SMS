<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class excusesRequest extends FormRequest
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
            'start_date' => [
                'string'
            ],
            'end_date' => [
                'string'
            ],
            'supporting_document' => [
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
            'added_by' => [
                'string'
            ],
            'edited_by' => [
                'string'
            ],
        ];
    }
}
