<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherFormRequest extends FormRequest
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
                'max:255'
            ],
            'fullname' => [
                'max:255'
            ],
            'class' => [
                'max:255'
            ],
            'gender' => [
                'max:255'
            ],
            'marital_status' => [
                'max:255'
            ],
            'dob' => [
                'max:255'
            ],
            'dofa' => [
                'max:255'
            ],
            
            'address' => [
                'max:255'
            ],
            'status' => [
                'max:255'
            ],
            'rank' => [
                'max:255'
            ],
            'promotion_yr' => [
                'max:255'
            ],
            'contact' => [
            ],
            'bank_branch' => [
                'max:255'
            ],
            'acct_name' => [
                'max:255'
            ],
            'acct_no' => [
                'max:255'
            ],
            'allowance' => [
                'max:255'
            ],
            'hometown' => [
                'max:255'
            ],
            'nok' => [
                'max:255'
            ],
            'relationship' => [
                'max:255'
            ],
            'contact_no' => [
                'max:255'
            ],
            'photo' => [
                'max:5000'
            ],
            'set' => [
                'max:255'
            ],

        ];
    }
}
