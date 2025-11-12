<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class FileUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                File::types(['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'csv'])
                    ->max(10240), // 10MB max
            ],
            'companyID' => 'nullable|integer|exists:companies,id',
            'personID' => 'nullable|integer|exists:persons,id',
            'orderID' => 'nullable|integer|exists:orders,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'A file is required.',
            'file.types' => 'The file must be one of: PDF, DOC, DOCX, XLS, XLSX, TXT, JPG, JPEG, PNG, GIF, CSV.',
            'file.max' => 'The file size must not exceed 10MB.',
            'companyID.exists' => 'The selected company does not exist.',
            'personID.exists' => 'The selected person does not exist.',
            'orderID.exists' => 'The selected order does not exist.',
        ];
    }
}

