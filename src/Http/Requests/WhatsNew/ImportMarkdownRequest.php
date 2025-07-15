<?php

namespace LaravelPlus\VersionPlatformManager\Http\Requests\WhatsNew;

use Illuminate\Foundation\Http\FormRequest;

class ImportMarkdownRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add authorization logic if needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'markdown_file' => 'required|file|mimes:md,txt|max:2048', // 2MB max
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'markdown_file.required' => 'Please select a Markdown file to import.',
            'markdown_file.file' => 'The uploaded file is invalid.',
            'markdown_file.mimes' => 'The file must be a Markdown (.md) or text (.txt) file.',
            'markdown_file.max' => 'The file size cannot exceed 2MB.',
        ];
    }

    /**
     * Get custom attributes for validation error messages.
     */
    public function attributes(): array
    {
        return [
            'markdown_file' => 'markdown file',
        ];
    }
} 