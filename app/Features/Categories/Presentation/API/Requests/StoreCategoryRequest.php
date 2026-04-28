<?php

namespace App\Features\Categories\Presentation\API\Requests;

use Illuminate\Foundation\Http\FormRequest;

class  StoreCategoryRequest extends FormRequest
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
            'name' => 'required|unique:categories,name|max:50',
        ];
    }
    public function messages()
    {
        return [
            'name.unique' => "Category name (:input) already exists",
        ];
    }
}
