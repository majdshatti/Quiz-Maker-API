<?php

namespace App\Http\Requests\Answer;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;
class AnswerCreateRequest extends FormRequest
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
             "questionId" => "required|string",
             "isCorrect" => "required|boolean",

    ];

        foreach ($langs as $lang) {
            $rules = array_merge($rules, [
                //$lang["code"] . ".name" => "required|string|unique:answer_translation,name",
                $lang["code"] . ".paragraph" => "required|string|max:400",
            ]);
        }
        return $rules;
    }
}
