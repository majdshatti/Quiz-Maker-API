<?php

namespace App\Http\Requests\Subject;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class SubjectEditRequest extends FormRequest
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
                $lang["code"] . ".name" => "string",
                $lang["code"] . ".description" => "string|min:10|max:400",
            ]);
        }
        return $rules;
    }
}
