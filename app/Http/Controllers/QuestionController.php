<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResException;
use App\Http\Requests\Question\QuestionEditRequest;
use App\Http\Requests\Question\QuestionStoreRequest;
use App\Models\Language;
use App\Models\Question;
use App\Models\QuestionTranslation;
use App\Models\Quiz;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    //* @desc:   Get a list of questions
    //* @route:  GET /api/question/
    //* @access: `USER`
    public function getQuestions(Request $request)
    {
        return sendSuccRes([
            "data" => Question::sort($request)
                ->filter(request(["paragraph"]))
                ->simplePaginate(),
        ]);
    }

    //* @desc:   Get a question by slug
    //* @route:  GET /api/question/{slug}
    //* @access: `USER`
    public function getQuestionByUuid(Request $request, $lang, string $uuid)
    {
        $question = Question::where("uuid", $uuid)->first();

        if (!$question) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $uuid])
            );
        }

        return sendSuccRes([
            "data" => $question,
        ]);
    }

    //* @desc:   Create a question
    //* @route:  POST /api/question
    //* @access: `USER`
    public function createQuestion(QuestionStoreRequest $request)
    {
        // Validated request body
        $body = $request->validated();

        // Init Question translations array
        $questionTranslations = [];

        // Retrieve all languages and loop throw them to get language_id
        $languages = Language::all();

        foreach ($languages as $lang) {
            array_push(
                $questionTranslations,
                new QuestionTranslation([
                    "uuid" => Str::orderedUuid()->getHex(),
                    "language_id" => $lang["id"],
                    "paragraph" => $body[$lang["code"]]["paragraph"],
                ])
            );
        }

        $subject = Subject::where(["uuid" => $body["subjectId"]])->first();
        if (!$subject) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => "subject"])
            );
        }

        $quiz = Quiz::where(["uuid" => $body["quizId"]])->first();
        if (!$quiz) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => "quiz"])
            );
        }

        // Get quiz_subject record
        $quizSubject = DB::table("quiz_subject")
            ->where([
                "subject_id" => $subject->id,
                "quiz_id" => $quiz->id,
            ])
            ->first();

        if (!$quizSubject) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => "quizSubject"])
            );
        }

        // Create question
        $question = Question::create([
            "uuid" => Str::orderedUuid()->getHex(),
            "quiz_subject_id" => $quizSubject->id,
        ]);

        // Save question's translations
        $translations = $question
            ->translations()
            ->saveMany($questionTranslations);

        // Fallback and delete question
        if (!$translations) {
            $question->delete();
            throw new ErrorResException(transResMessage("serverError"));
        }

        return sendSuccRes(
            [
                "message" => transResMessage("created", ["path" => "question"]),
                "data" => [
                    "question" => $question,
                    "translations" => $questionTranslations,
                ],
            ],
            201
        );
    }

    //* @desc:   Edit a question
    //* @route:  PUT /api/question/{slug}
    //* @access: `ADMIN`
    public function editQuestion(
        QuestionEditRequest $request,
        $lang,
        string $uuid
    ) {
        $body = $request->validated();

        $question = Question::where("uuid", $uuid)->first();

        if (!$question) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $uuid]),
                404
            );
        }

        for ($i = 0; $i < count($question->translations); $i++) {
            // Check if the element was a en record

            if ($question->translations[$i]->language["code"] === "en") {
                $question->translations[$i]["paragraph"] =
                    $body["en"]["paragraph"] ??
                    $question->translations[$i]["paragraph"];
            }
            // Check if the element was a ar record
            elseif ($question->translations[$i]->language["code"] === "ar") {
                $question->translations[$i]["paragraph"] =
                    $body["ar"]["paragraph"] ??
                    $question->translations[$i]["paragraph"];
            }
        }

        $question->save();
        $question->translations()->saveMany($question->translations);

        return sendSuccRes(["data" => $question]);
    }

    //* @desc:   Delete a question with its translations
    //* @route:  DELETE /api/question/{uuid}
    //* @access: `ADMIN`
    public function deleteQuestion(Request $request, $lang, string $uuid)
    {
        $question = Question::where("uuid", $uuid)->first();

        if (!$question) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $uuid])
            );
        }

        $question->delete();

        return sendSuccRes([
            "message" => transResMessage("deleted", ["path" => "subject"]),
        ]);
    }
}
