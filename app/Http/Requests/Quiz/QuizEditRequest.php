<?php

namespace App\Http\Requests\Quiz;
use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class QuizEditRequest extends FormRequest
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

        $rules = [];

        foreach ($langs as $lang) {
            $rules = array_merge($rules, [
                $lang["code"] . ".name" => "string|unique:quiz_translation,name",
                $lang["code"] . ".description" => "string|min:10|max:400",
            ]);
        }

        return $rules;
    }
}
