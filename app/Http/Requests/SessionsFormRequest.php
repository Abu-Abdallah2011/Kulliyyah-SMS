<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionsFormRequest extends FormRequest
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
            'session' => [
                'max:255'
            ],
            'term' => [
                'max:255'
            ],
            'term_starts' => [
                'max:255'
            ],
            'term_ends' => [
                'max:255'
            ],
            'next_term_starts' => [
                'max:255'
            ],
        ];
    }
}
