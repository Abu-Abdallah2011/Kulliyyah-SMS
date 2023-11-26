<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class subjectsFormRequest extends FormRequest
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
            'subject' => [
                'max:255'
            ],
            'marks_obtainable' => [
                'max:255'
            ],
        ];
    }
}
