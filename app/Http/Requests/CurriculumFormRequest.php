<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurriculumFormRequest extends FormRequest
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
            'date' => [
                'required',
                'max:255'
            ],
            'class' => [
                'max:255'
            ],
            'sura' => [
                'max:255'
            ],
            'from' => [
                'max:255'
            ],
            'to' => [
                'max:255'
            ],
            'times' => [
                'max:255'
            ],
            'bita' => [
                'max:255'
            ],
            'grade' => [
                'max:255'
            ],
            'hadda' => [
                'required',
                'max:255'
            ],
            'comment' => [
                'min:0'
            ],
            // 'teacher' => [
            //     'required',
            //     'max:255'
            // ],
            'set' => [
                'max:255'
            ],
        ];
    }
}
