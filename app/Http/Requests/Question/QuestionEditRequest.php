<?php

namespace App\Http\Requests\Question;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class QuestionEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $langs = Language::all();

        $rules = [
            "subjectId" => "exists:subject,uuid",
            "quizId" => "exists:quiz,uuid",
        ];

        foreach ($langs as $lang) {
            $rules = array_merge($rules, [
                $lang["code"] . ".paragraph" => "string|unique:question_translation,paragraph",
            ]);
        }
        return $rules;
    }
}
