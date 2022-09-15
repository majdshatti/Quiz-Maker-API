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

        Quiz::factory(10)->create();
        
        QuizTranslation::factory(10)->create();
    }
}
