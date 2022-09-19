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
        

        jsonSeeder("subject","database/data/subject.json");

        jsonSeeder("subject_translation","database/data/subjectTranslation.json");

        //***********************/
        //******** QUIZ *********/
        //***********************/

        jsonSeeder("quiz","database/data/quiz.json");

        jsonSeeder("quiz_translation","database/data/quizTranslation.json");

        //*******************************/
        //******** SUBJECT QUIZ *********/
        //*******************************/

        jsonSeeder("quiz_subject","database/data/quizSubject.json");


        //*******************************/
        //********** QUESTION ***********/
        //*******************************/

        jsonSeeder("question","database/data/question.json");

        jsonSeeder("question_translation","database/data/questionTranslation.json");

        //*******************************/
        //********** Answer ***********/
        //*******************************/

        //jsonSeeder("answer","database/data/answer.json");

        //jsonSeeder("answer_translation","database/data/answerTranslation.json");

    }
}
