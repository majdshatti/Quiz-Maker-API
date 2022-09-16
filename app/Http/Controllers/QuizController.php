<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Quiz;
use App\Models\QuizTranslation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exceptions\ErrorResException;
use App\Http\Requests\Quiz\QuizCreateRequest;
use App\Http\Requests\Quiz\QuizEditRequest;

class QuizController extends Controller
{

    //* @desc:   Get a list of quiz
    //* @route:  GET /api/quiz/
    //* @access: `USER`
    public function getQuiz(Request $request)
    {
        return sendSuccRes([
            "data" => Quiz::sort($request)
                ->filter(request(["slug", "name", "search"]))
                ->simplePaginate(),
        ]);
    }

    //* @desc:   Get a quiz by slug
    //* @route:  GET /api/quiz/{slug}
    //* @access: `USER`
    public function getQuizBySlug(Request $request, $lang, string $slug)
    {
        $quiz = Quiz::where("slug", $slug)->first();

        if (!$quiz) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $slug])
            );
        }

        return sendSuccRes([
            "data" => $quiz,
        ]);
    }

    //* @desc:   Create a quiz
    //* @route:  POST /api/quiz
    //* @access: `USER`
    public function createQuiz(QuizCreateRequest $request)
    {
        // Validated request body
        $body = $request->validated();

        // Init quiz translations array
        $quizTranslations = [];

        // English name to be slugified and stored in quiz
        $nameToBeSlugified = "";

        // Retrieve all languages and loop throw them to get language_id
        $languages = Language::all();

        foreach ($languages as $lang) {
            array_push(
                $quizTranslations,
                new QuizTranslation([
                    "uuid" => Str::orderedUuid()->getHex(),
                    "language_id" => $lang["id"],
                    "name" => $body[$lang["code"]]["name"],
                    "description" => $body[$lang["code"]]["description"],
                ])
            );
            // Catch english name
            if ($lang["code"] === "en") {
                $nameToBeSlugified = $body[$lang["code"]]["name"];
            }
        }

        // Create quiz
        $quiz = Quiz::create([
            "uuid" => Str::orderedUuid()->getHex(),
            "slug" => Str::slug($nameToBeSlugified),
        ]);

        // Save quiz's translations
        $translations = $quiz
            ->translations()
            ->saveMany($quizTranslations);

        // Fallback and delete quiz
        if (!$translations) {
            $quiz->delete();
            throw new ErrorResException(transResMessage("serverError"));
        }

        return sendSuccRes(
            [
                "message" => transResMessage("created", ["path" => "quiz"]),
                "data" => [
                    "quiz" => $quiz,
                    "translations" => $quizTranslations,
                ],
            ],
            201
        );
    }

    //* @desc:   Edit a quiz
    //* @route:  PUT /api/quiz/{slug}
    //* @access: `ADMIN`
    public function editQuiz(
        QuizCreateRequest $request,
        $lang,
        string $slug
    ) {
        $body = $request->validated();

        $quiz = Quiz::where("slug", $slug)->first();

        if (!$quiz) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $slug]),
                404
            );
        }
        
        // Adjust body if `en name` exists in the request body
        if (
            array_key_exists("en", $body) &&
            array_key_exists("name", $body["en"])
        ) {
            $body["en"]["slug"] = Str::slug($body["en"]["name"]);
        }

        for ($i = 0; $i < count($quiz->translations); $i++) {
            // Check if the element was a en record
            if ($quiz->translations[$i]->language["code"] === "en") {
                $quiz->translations[$i]["name"] =
                    $body["en"]["name"] ?? $quiz->translations[$i]["name"];
                $quiz->translations[$i]["description"] =
                    $body["en"]["description"] ??
                    $quiz->translations[$i]["description"];
            }
            // Check if the element was a ar record
            elseif ($quiz->translations[$i]->language["code"] === "ar") {
                $quiz->translations[$i]["name"] =
                    $body["ar"]["name"] ?? $quiz->translations[$i]["name"];
                $quiz->translations[$i]["description"] =
                    $body["ar"]["description"] ??
                    $quiz->translations[$i]["description"];
            }
        }
        // Edit slug if exist on the adjusted body
        $quiz["slug"] = $body["en"]["slug"] ?? $quiz["slug"];

        $quiz->save();

        return sendSuccRes(["data" => $quiz]);
    }

    //* @desc:   Delete a quiz with its translations
    //* @route:  DELETE /api/quiz/{slug}
    //* @access: `ADMIN`
    public function deleteQuiz(
        QuizEditRequest $request,
        $lang,
        string $slug
    ) {
        $quiz = Quiz::where("slug", $slug)->first();

        if (!$quiz) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $slug])
            );
        }

        $quiz->delete();

        return sendSuccRes([
            "message" => transResMessage("deleted", ["path" => "quiz"]),
        ]);
    }
}
