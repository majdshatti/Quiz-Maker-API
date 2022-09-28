<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\TakenQuiz;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User
        jsonSeeder("user", "database/data/user.json");

        // Language
        jsonSeeder("language", "database/data/language.json");

        // Subject
        jsonSeeder("subject", "database/data/subject.json");
        jsonSeeder(
            "subject_translation",
            "database/data/subjectTranslation.json"
        );

        // Quiz
        jsonSeeder("quiz", "database/data/quiz.json");
        jsonSeeder("quiz_translation", "database/data/quizTranslation.json");

        // Subject Quiz Many to Many table
        jsonSeeder("quiz_subject", "database/data/quizSubject.json");

        // Qustion
        jsonSeeder("question", "database/data/question.json");
        jsonSeeder(
            "question_translation",
            "database/data/questionTranslation.json"
        );

        // Answer
        jsonSeeder("answer", "database/data/answer.json");
        jsonSeeder(
            "answer_translation",
            "database/data/answerTranslation.json"
        );

        TakenQuiz::create([
            "id" => 1,
            "uuid" => "213124125ewc3",
            "score" => 0,
            "status" => "active",
            "subject_quiz_id" => 1,
            "user_id" => 14,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
