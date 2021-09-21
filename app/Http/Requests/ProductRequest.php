<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Rules\Sara;

class ProductRequest extends FormRequest
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
            'name_id' => ['required','min:3', new Sara],          
            'name_en' => ['required','min:3', new Sara],
            'harga' => 'numeric',
            'stock' => 'numeric'
        ];

        if (Request::input('harga_discount')!='') {
            $rules['harga_discount'] = 'numeric';
        }

        if (Request::input('uploaded')=='0') {
            $rules['photo'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
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
            'max' => ':attribute max :max characters',
            'digits_between' => ':attribute digits beetwen 1 and 16',       
            'sara' => ':attribute contains SARA',
            'numeric' => ':attribute must be number',
            'email' => 'Email format invalid',
            'image' => 'Image invalid',
            'mimes' => 'Image format invalid'
        ];
    }
}
