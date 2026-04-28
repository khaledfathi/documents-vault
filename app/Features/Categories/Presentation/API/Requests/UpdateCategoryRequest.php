<?php

namespace App\Features\Categories\Presentation\API\Requests;

use Illuminate\Foundation\Http\FormRequest;

class  UpdateCategoryRequest extends FormRequest
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
            'name' => 'required|max:50|unique:categories,name,'.$this->route('category'),
        ];
    }
    public function messages()
    {
        return [
            'name.unique' => "Category name (:input) already exists",
        ];
    }
}
