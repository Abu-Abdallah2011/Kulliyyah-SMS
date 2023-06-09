<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentFormRequest extends FormRequest
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
            
            'fullname' => [
                'required',
                'max:255'
            ],
            'class' => [
                'required',
                'max:255'
            ],
            'gender' => [
                'required',
                'max:255'
            ],
            'dob' => [
                'required',
                'max:255'
            ],
            'doa' => [
                'required',
                'max:255'
            ],
            'reg_fee' => [
                'required',
                'max:255'
            ],
            'address' => [
                'required',
                'max:255'
            ],
            'status' => [
                'required',
                'max:255'
            ],
            'guardian_id' => [
                'required',
                'max:255'
            ],
            // 'grad_type' => [
            //     'max:255'
            // ],
            // 'mock_fee' => [
            //     'max:255'
            // ],
            // 'grad_fee' => [
            //     'max:255'
            // ],
            // 'grad_date' => [
            //     'max:255'
            // ],
            // 'grad_yr' => [
            //     'max:255'
            // ],
            'photo' => [
                'max:255'
            ],
            'relationship' => [
                'max:255'
            ],
            'set' => [
                'max:255'
            ],

        ];
    }
}
