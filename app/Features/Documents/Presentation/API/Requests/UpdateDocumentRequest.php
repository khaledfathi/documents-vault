<?php

namespace App\Features\Documents\Presentation\API\Requests;

use App\Features\Documents\Infrastructure\Validation\Rules\TotalFilesSize;
use App\Shared\Domain\Enums\Document\DocumentVisibilityType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateDocumentRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'doc_date' => 'date',
            'doc_expire_date' => 'date',
            'visibility' => new Enum(DocumentVisibilityType::class),
            'doc_number' => Rule::unique('documents')->where(function ($query) {
                return $query->where('user_id', $this->user_id);
            })->ignore($this->route('document')),
            'categories' => 'required|array|min:1',
            'categories.*' => 'integer|exists:categories,id',
            // max file size 150MB
            'files' => [
                'sometimes',
                'array',
                'max:20', // max is 20 files
                new TotalFilesSize(160), // total maxsize in Mega Bytes
            ],
            'files.*' => 'file|mimes:pdf,docx,jpg,png',
        ];
    }
}
