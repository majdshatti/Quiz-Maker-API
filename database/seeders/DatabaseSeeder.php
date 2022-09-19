<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Language;
use App\Models\Question;
use App\Models\QuestionTranslation;
use App\Models\Quiz;
use App\Models\QuizTranslation;
use App\Models\Subject;
use App\Models\SubjectTranslation;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //***********************/
        //******** USER *********/
        //***********************/

        // Admin
        User::create([
            "uuid" => Str::orderedUuid()->getHex(),
            "name" => "Blackpearl",
            "slug" => "blackpearl",
            "email" => "blackpearl@gmail.com",
            "password" => bcrypt("vip123456"),
        ]);

        // Users
        User::factory(10)->create([
            "password" => bcrypt("vip123456"),
        ]);

        //***********************/
        //******** LANG *********/
        //***********************/

        // Default languages
        $languages = Language::insert([
            [
                "name" => "English",
                "code" => "en",
                "uuid" => Str::orderedUuid()->getHex(),
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ],
            [
                "name" => "Arabic",
                "code" => "ar",
                "uuid" => Str::orderedUuid()->getHex(),
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ],
        ]);

        //***********************/
        //******* SUBJECT *******/
        //***********************/
        
        //$subjectJson = File::get("database/data/subject.json");
        $subjectTransJson = File::get("database/data/subjectTranslation.json");

        //$subjects = json_decode($subjectJson);
        $subjectsTrans = json_decode($subjectTransJson);

        /*
        foreach ($subjects as $key => $value) {
            Subject::create([
                "id" => $value->id,
                "slug" => $value->slug,
                "uuid" => $value->uuid,
            ]);
        }*/

        jsonSeeder("subject","database/data/subject.json");



        foreach ($subjectsTrans as $key => $value) {
            SubjectTranslation::create([
                "id" => $value->id,
                "uuid" => $value->uuid,
                "language_id" => $value->language_id,
                "subject_id" => $value->subject_id,
                "name" => $value->name,
                "description" => $value->description,
            ]);
        }

        //***********************/
        //******** QUIZ *********/
        //***********************/

        $quizJson = File::get("database/data/quiz.json");
        $quizTransJson = File::get("database/data/quizTranslation.json");

        $quizzes = json_decode($quizJson);
        $quizzesTrans = json_decode($quizTransJson);

        foreach ($quizzes as $key => $value) {
            Quiz::create([
                "id" => $value->id,
                "slug" => $value->slug,
                "uuid" => $value->uuid,
            ]);
        }

        foreach ($quizzesTrans as $key => $value) {
            QuizTranslation::create([
                "id" => $value->id,
                "uuid" => $value->uuid,
                "language_id" => $value->language_id,
                "quiz_id" => $value->quiz_id,
                "name" => $value->name,
                "description" => $value->description,
            ]);
        }

        //*******************************/
        //******** SUBJECT QUIZ *********/
        //*******************************/

        $subjectQuizJson = File::get("database/data/quizSubject.json");

        $subjectQuiz = json_decode($subjectQuizJson);

        foreach ($subjectQuiz as $key => $value) {
            DB::table("quiz_subject")->insert([
                "id" => $value->id,
                "quiz_id" => $value->quiz_id,
                "subject_id" => $value->subject_id,
            ]);
        }

        //*******************************/
        //********** QUESTION ***********/
        //*******************************/

        $questionJson = File::get("database/data/question.json");
        $questionTransJson = File::get(
            "database/data/questionTranslation.json"
        );

        $questions = json_decode($questionJson);
        $questionsTrans = json_decode($questionTransJson);

        foreach ($questions as $key => $value) {
            Question::create([
                "id" => $value->id,
                "quiz_subject_id" => $value->quiz_subject_id,
                "uuid" => $value->uuid,
            ]);
        }

        foreach ($questionsTrans as $key => $value) {
            QuestionTranslation::create([
                "id" => $value->id,
                "uuid" => $value->uuid,
                "language_id" => $value->language_id,
                "question_id" => $value->question_id,
                "paragraph" => $value->paragraph,
            ]);
        }
    }
}
