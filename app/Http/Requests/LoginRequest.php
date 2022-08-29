<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'     => 'required|string|email',
            'password'  => 'required|string',
        ];
    }

    /**
     * Return failed validation messages
     *
     * @return json
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => 'error',
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
