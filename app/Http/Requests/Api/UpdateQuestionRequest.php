<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'question'    => ['sometimes', 'required', 'string'],
            'image'       => ['nullable', 'string', 'max:255'],
            'difficulty'  => ['nullable', Rule::in(['easy', 'medium', 'hard'])],
            'explanation' => ['nullable', 'string'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }
}
