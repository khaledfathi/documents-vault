<?php

namespace App\Features\Users\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|max:30',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore((int)$this->route('user')),
            ],

            'phones.*' => [
                Rule::unique('phones', 'phone')->ignore((int)$this->route('user'), 'user_id'),
            ],
        ];
    }
    public function messages()
    {
        return [
            'phones.*.unique' => "phone (:input) already exists",
        ];
    }
}
