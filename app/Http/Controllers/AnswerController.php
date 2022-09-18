<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Answer\AnswerCreateRequest;
use App\Models\Answer;
use App\Exceptions\ErrorResException;
use App\Http\Requests\Answer\AnswerEditRequest;
use Illuminate\Support\Str;
use App\Models\Language;
use App\Models\AnswerTranslation;

class AnswerController extends Controller
{
    //create answer , delete answer , edit answer
    //Create Answer :
    //max 4 answers 
    //all 4 answers are not isCorrect = false  //must be one true
    //no more than true answer 

    //* @desc:   Get a list of answer
    //* @route:  GET /api/answer/
    //* @access: `USER`
    public function getAnswer(Request $request)
    {
        return sendSuccRes([
            "data" => Answer::sort($request)
                ->filter(request(["answerId", "paragraph", "search"]))
                ->simplePaginate(),
        ]);
    }


    public function createQuiz(AnswerCreateRequest $request)
    {
        // Validated request body
        $body = $request->validated();

        // Init answer translations array
        $answerTranslations = [];

        // Retrieve all languages and loop throw them to get language_id
        $languages = Language::all();

        foreach ($languages as $lang) {
            array_push(
                $answerTranslations,
                new AnswerTranslation([
                    "uuid" => Str::orderedUuid()->getHex(),
                    "language_id" => $lang["id"],
                    "answer_id" => $body["answerId"],
                    "paragraph" => $body[$lang["code"]]["paragraph"],
                ])
            );
        }

        //Get all the answers that have the same question_id
        $questionAnswers = Answer::where("question_id", $body["questionId"]);

        //check if there is no more than 4 answers for each question
        $numberOfAnswers = count($questionAnswers);

        //check if there is one correct answer

        //check the is correct that comes from the body


        // Create answer
        $answer = Answer::create([
            "uuid" => Str::orderedUuid()->getHex(),
            "question_id" => $body["questionId"],
            "isCorrect" => $body["isCorrect"],
        ]);

        // Save answer's translations
        $translations = $answer
            ->translations()
            ->saveMany($answerTranslations);
        
        // Fallback and delete answer
        if (!$translations) {
            $answer->delete();
            throw new ErrorResException(transResMessage("serverError"));
        }

        return sendSuccRes(
            [
                "message" => transResMessage("created", ["path" => "answer"]),
                "data" => [
                    "quiz" => $answer,
                    "translations" => $answerTranslations,
                ],
            ],
            201
        );
    
    }
    //!

    //* @desc:   Edit a answer
    //* @route:  PUT /api/answer/{uuid}
    //* @access: `ADMIN`
    public function editAnswer(
        AnswerEditRequest $request,
        $lang,
        string $uuid
    ) {
        $body = $request->validated();

        $answer = Answer::where("uuid", $uuid)->first();

        if (!$answer) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $uuid]),
                404
            );
        }


        for ($i = 0; $i < count($answer->translations); $i++) {
            // Check if the element was a en record
            if ($answer->translations[$i]->language["code"] === "en") {
                $answer->translations[$i]["name"] =
                    $body["en"]["name"] ?? $answer->translations[$i]["name"];
                $answer->translations[$i]["description"] =
                    $body["en"]["description"] ??
                    $answer->translations[$i]["description"];
            }
            // Check if the element was a ar record
            elseif ($answer->translations[$i]->language["code"] === "ar") {
                $answer->translations[$i]["name"] =
                    $body["ar"]["name"] ?? $answer->translations[$i]["name"];
                $answer->translations[$i]["description"] =
                    $body["ar"]["description"] ??
                    $answer->translations[$i]["description"];
            }
        }

        $answer->save();

        $answer->translations()->saveMany($answer->translations);

        return sendSuccRes(["data" => $answer]);
    }


    //* @desc:   Delete an answer with its translations
    //* @route:  DELETE /api/answer/{uuid}
    //* @access: `ADMIN`
    public function deleteAnswer(
        AnswerEditRequest $request,
        $lang,
        string $uuid
    ) {
        $answer = Answer::where("slug", $uuid)->first();

        if (!$answer) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $uuid])
            );
        }

        $answer->delete();

        return sendSuccRes([
            "message" => transResMessage("deleted", ["path" => "answer"]),
        ]);
    }
}
