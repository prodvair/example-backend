<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class UpdateUserPasswordRequest extends FormRequest
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
            'old_password'     => ['required', 'min:6', function ($attribute, $value, $fail) {
                $user = Auth::user();
                if (!\Hash::check($value, $user->password)) {
                    return $fail(__('The old password is incorrect.'));
                }
            }],
            'new_password'     => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
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
