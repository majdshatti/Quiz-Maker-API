<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Quiz;
use App\Models\QuizTranslation;
use Faker\Generator as Faker;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizTranslation>
 */
class QuizTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition()
    {
        $quizIds = Quiz::all()->pluck('id')->toArray();
        $languageIds = Language::all()->pluck('id')->toArray();

        $quiz_id = $this->faker->randomElement($quizIds);
        $language_id = $this->faker->randomElement($languageIds);
        return [
            "uuid" => Str::orderedUuid()->getHex(),
            "name" => fake()->name(),
            "description" => fake()->text(),
            'quiz_id' =>$quiz_id ,
            'language_id' => $language_id,
        ];
    }
}