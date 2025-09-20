<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StorePollRequest extends FormRequest
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
            'question' => ['required', 'string', 'max:500', 'min:10'],
            'options' => ['required', 'array', 'min:2', 'max:10'],
            'options.*' => ['required', 'string', 'max:200', 'min:1', 'distinct'],
            'expiresAt' => ['nullable', 'date', 'after:now'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'question.required' => 'Poll question is required.',
            'question.min' => 'Poll question must be at least 10 characters.',
            'question.max' => 'Poll question cannot exceed 500 characters.',
            'options.required' => 'Poll options are required.',
            'options.min' => 'At least 2 options are required.',
            'options.max' => 'Maximum 10 options are allowed.',
            'options.*.required' => 'All options must have text.',
            'options.*.min' => 'Each option must have at least 1 character.',
            'options.*.max' => 'Each option cannot exceed 200 characters.',
            'options.*.distinct' => 'All options must be unique.',
            'expiresAt.after' => 'Expiry date must be in the future.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Filter out empty options
        if ($this->has('options')) {
            $this->merge([
                'options' => array_values(array_filter(
                    $this->options,
                    fn($option) => !empty(trim($option))
                ))
            ]);
        }

        // Convert expiresAt to proper format if provided
        if ($this->expiresAt && !empty($this->expiresAt)) {
            $this->merge([
                'expiresAt' => date('Y-m-d H:i:s', strtotime($this->expiresAt))
            ]);
        }
    }

    /**
     * Get validated data with additional processing.
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        
        // Generate unique slug for the poll
        $baseSlug = Str::slug($validated['question']);
        $slug = $baseSlug;
        $counter = 1;
        
        // Ensure slug is unique
        while (\App\Models\Poll::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['user_id'] = auth()->id();
        
        // Handle empty expiresAt
        if (empty($validated['expiresAt'])) {
            $validated['expires_at'] = null;
        } else {
            $validated['expires_at'] = $validated['expiresAt'];
        }
        
        unset($validated['expiresAt']);
        
        return $validated;
    }
}