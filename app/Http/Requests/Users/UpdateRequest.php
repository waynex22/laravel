<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'phone' => 'required|unique:users,phone,'.$this->user,
            'gender' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,PNG,jpec',
            'password' => 'nullable|min:8',
            'email' => 'required|email|unique:users,email,'.$this->user,
        ];
    }
}
