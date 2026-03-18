<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'question'    => ['required', 'string'],
            'image'       => ['nullable', 'string', 'max:255'],
            'difficulty'  => ['nullable', Rule::in(['easy', 'medium', 'hard'])],
            'explanation' => ['nullable', 'string'],
            'is_active'   => ['nullable', 'boolean'],
            'answers'     => ['nullable', 'array', 'min:2'],
            'answers.*.answer'     => ['required_with:answers', 'string'],
            'answers.*.is_correct' => ['required_with:answers', 'boolean'],
        ];
    }
}
