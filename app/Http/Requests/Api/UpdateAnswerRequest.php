<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answer'     => ['sometimes', 'required', 'string'],
            'is_correct' => ['sometimes', 'required', 'boolean'],
        ];
    }
}
