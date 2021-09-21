<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Sara;

class UserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            'firstName' => ['required','min:3', new Sara],
            'lastName' => [new Sara],
            'phoneNumber' => 'required|numeric|digits_between:1,16',
            'role' => 'required',
            'email' => 'email:rfc,dns'
        ];  
        
        if (Request::input('id')=='') {
            $rules['password'] = 'required|min:6|confirmed';
        }else{
            if(strlen(Request::input('password')) > 0){
                $rules['password'] = 'min:6|confirmed';
            }
        }

        return $rules;
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => ':attribute required',
            'min' => ':attribute min :min characters',
            'digits_between' => ':attribute digits beetwen 1 and 16',       
            'sara' => ':attribute contains SARA',
            'numeric' => ':attribute must be number',
            'confirmed' => 'Retype password incorrect',
            'email' => 'Format Email salah'
        ];
    }
}
