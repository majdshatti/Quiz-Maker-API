<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Language;

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
        $subjectJson = File::get("database/data/subject.json");
        $subjectTransJson = File::get("database/data/subjectTranslation.json");

        $subjects = json_decode($subjectJson);
        $subjectsTrans = json_decode($subjectTransJson);

        foreach ($subjects as $key => $value) {
            Subject::create([
                "id" => $value->id,
                "slug" => $value->slug,
                "uuid" => $value->uuid,
            ]);
        }

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
        Quiz::insert([
            [
                "uuid" => Str::orderedUuid()->getHex(),
                "slug" => "beginner",
            ],
            [
                "uuid" => Str::orderedUuid()->getHex(),
                "slug" => "intermediate",
            ],
            [
                "uuid" => Str::orderedUuid()->getHex(),
                "slug" => "advanced",
            ],
        ]);

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

        // Quiz::factory(10)->create();

        // QuizTranslation::factory(10)->create();
    }
}
