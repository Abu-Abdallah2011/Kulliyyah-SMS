<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardianFormRequest extends FormRequest
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
            'user_id' => [
                'required',
                'max:255'
            ],
            'teacher_id' => [
                'max:255'
            ],
            'fullname' => [
                'required',
                'max:255'
            ],
            'address' => [
                'required',
                'max:255'
            ],
            'phone' => [
                'required',
                'max:255'
            ],
        ];
    }
}
