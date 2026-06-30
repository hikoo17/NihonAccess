<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class SubmitWritingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'writing_exercise_id' => ['required', 'exists:writing_exercises,id'],
            'submission_image' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
            'score' => ['nullable', 'integer', 'min:0', 'max:100'],
        ];
    }
}
