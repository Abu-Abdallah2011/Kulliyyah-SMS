<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'id' => [
                'required',
                'max:255'
            ],
            'name' => [
                'required',
                'max:255'
            ],
            'username' => [
                'required',
                'max:255'
            ],
            'role' => [
                'required',
                'max:255'
            ],
            'email' => [
                'email',
                'max:255'
            ],
        ];
    }
}
