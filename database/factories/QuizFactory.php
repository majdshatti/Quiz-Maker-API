<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Quiz;
use App\Models\QuizTranslation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        /*
        $quizSlugs = QuizTranslation::all()->pluck('name')->toArray();
        $quiz_slug = $this->faker->randomElement($quizSlugs);
        $quiz_slug = Str::slug($quiz_slug);
        */

        $quiz_slug = fake()->slug();

        return [
            "uuid" => Str::orderedUuid()->getHex(),
            "slug" => $quiz_slug,
        ];
    }
}
