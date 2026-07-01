<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class SubmitCharacterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'character_exercise_id' => ['required', 'exists:character_exercises,id'],
            'answer' => ['required', 'string', 'max:255'],
        ];
    }
}
