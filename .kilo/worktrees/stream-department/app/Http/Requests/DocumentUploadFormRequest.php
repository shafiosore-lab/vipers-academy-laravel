<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentUploadFormRequest extends FormRequest
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
     */
    public function rules(): array
    {
        // Get user and document type to determine allowed mimes and max size
        $user = $this->user();
        $documentType = $this->route('documentType');

        $documentUploadController = new \App\Http\Controllers\DocumentUploadController();
        $requiredDocuments = $documentUploadController->getRequiredDocumentTypes($user);

        $rules = [
            'document_file' => [
                'required',
                'file',
                Rule::when(
                    isset($requiredDocuments[$documentType]['max_size']),
                    'max:' . $requiredDocuments[$documentType]['max_size']
                ),
                Rule::when(
                    isset($requiredDocuments[$documentType]['allowed_mimes']),
                    'mimes:' . implode(',', $requiredDocuments[$documentType]['allowed_mimes'])
                ),
            ],
            'notes' => 'nullable|string|max:500',
        ];

        return $rules;
    }
}
