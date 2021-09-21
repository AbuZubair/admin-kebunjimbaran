<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Rules\Sara;

class PromoRequest extends FormRequest
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
            'promo_name' => ['required','min:1', new Sara],      
            'kode_promo' => ['required','min:3', new Sara],      
        ];

        if (Request::input('discount')=='' && Request::input('discount_amount')=='') {
            $rules['discount'] = 'required';
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
            'email' => 'Email format invalid',
        ];
    }
}
