<?php

namespace LaravelPlus\VersionPlatformManager\Http\Requests\WhatsNew;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWhatsNewRequest extends FormRequest
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
            'platform_version_id' => 'required|exists:platform_versions,id',
            'title' => 'required|string|max:255|min:3',
            'content' => 'required|string|min:10',
            'type' => 'required|in:feature,improvement,bugfix,security,deprecation',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'platform_version_id.required' => 'Please select a platform version.',
            'platform_version_id.exists' => 'The selected platform version is invalid.',
            'title.required' => 'Title is required.',
            'title.min' => 'Title must be at least 3 characters.',
            'title.max' => 'Title cannot exceed 255 characters.',
            'content.required' => 'Content is required.',
            'content.min' => 'Content must be at least 10 characters.',
            'type.required' => 'Please select a type.',
            'type.in' => 'Please select a valid type.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order must be 0 or greater.',
        ];
    }

    /**
     * Get custom attributes for validation error messages.
     */
    public function attributes(): array
    {
        return [
            'platform_version_id' => 'platform version',
            'title' => 'title',
            'content' => 'content',
            'type' => 'type',
            'is_active' => 'active status',
            'sort_order' => 'sort order',
        ];
    }
} 