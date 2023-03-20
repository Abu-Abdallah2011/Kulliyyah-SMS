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
                'required',
                'max:255'
            ],
            'class' => [
                'max:255'
            ],
            'gender' => [
                'required',
                'max:255'
            ],
            'marital_status' => [
                'required',
                'max:255'
            ],
            'dob' => [
                'required',
                'max:255'
            ],
            'dofa' => [
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
            'rank' => [
                'required',
                'max:255'
            ],
            'promotion_yr' => [
                'max:255'
            ],
            'contact' => [
                'required',
            ],
            'bank_branch' => [
                'required',
                'max:255'
            ],
            'acct_name' => [
                'required',
                'max:255'
            ],
            'acct_no' => [
                'required',
                'max:255'
            ],
            'allowance' => [
                'required',
                'max:255'
            ],
            'hometown' => [
                'max:255'
            ],
            'nok' => [
                'required',
                'max:255'
            ],
            'relationship' => [
                'required',
                'max:255'
            ],
            'contact_no' => [
                'required',
                'max:255'
            ],
            'photo' => [
                'max:255'
            ],

        ];
    }
}
