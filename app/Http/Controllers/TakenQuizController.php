<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResException;
use App\Http\Requests\TakenQuiz\TakenQuizRequest;
use App\Models\Quiz;
use App\Models\QuizSubject;
use App\Models\Subject;
use App\Models\TakenQuiz;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TakenQuizController extends Controller
{
    //* @desc:   Get a list of taken quizzes
    //* @route:  GET /api/subject/{subjectSlug}/quiz/{quizSlug}
    //* @access: `ADMIN`
    public function getQuizTaken(Request $request)
    {
        return sendSuccRes([
            "data" => TakenQuiz::sort($request)
                ->filter(request(["status", "score", "name", "search"]))
                ->simplePaginate(),
        ]);
    }

    //* @desc:   Take a quiz
    //* @route:  POST /api/subject/{subjectSlug}/quiz/{quizSlug}/take
    //* @access: `USER`

    function takeQuiz($request, $subjectSlug, $quizSlug)
    {
        //last time from a month
        //user verified
        //subject quiz exist
        //Quiz level
        $quiz = Quiz::where("slug", $quizSlug)->first();

        if (!$quiz) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $quizSlug]),
                404
            );
        }

        $subject = Subject::where("slug", $subjectSlug)->first();;

        if (!$subject) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $subjectSlug]),
                404
            );
        }

        $userID = Auth::user()->id;
        $user = User::where("id", $userID)->first();

        if ($user["email_verified_at"] == null) {
            throw new ErrorResException(
                transResMessage("unverified"),
                404
            );
        }

        $takenQuiz = TakenQuiz::where("user_id", $user["id"])->orderBy("created_at")->first();

        if ($takenQuiz) {
            //Active
            $currentActiveQuizzes = $user->takenQuizzes->where("status", "active")->count();
            if ($currentActiveQuizzes > 0) {
                throw new ErrorResException(
                    transResMessage("quizActive"),
                    404
                );
            }

            $subjectQuiz = QuizSubject::where(["quiz_id" => $quiz["id"], "subject_id" => $subject["id"]])->first();
            $quizSubjectTaken = $user->takenQuizzes->where("subject_quiz_id", $subjectQuiz["id"])->first();

            if (!is_null($quizSubjectTaken)) {
                //Pass
                if ($quizSubjectTaken["status"] == "pass") {
                    throw new ErrorResException(
                        transResMessage("quizPass"),
                        404
                    );
                }

                //Time
                $nowDate = Carbon::now();
                $createdDate = new Carbon($takenQuiz["created_at"]);
                $createdDate->addMonth();
                $result = $nowDate->greaterThanOrEqualTo($createdDate);
                if (!$result) {
                    $diff = Carbon::parse($createdDate)->diffForHumans($nowDate);
                    throw new ErrorResException(
                        transResMessage("lessThanMonth", ["value" => $diff]),
                        404
                    );
                }
            }

            //Level
            $quizLevel = $subjectQuiz->quiz->first();
            $subjectSlug = $subjectQuiz->subject->first();
            if (
                $quizLevel["level"] == $quiz["level"]
                && $subjectSlug["slug"] == $subject["slug"]
            ) {
                throw new ErrorResException(
                    transResMessage("level", ["value" => $quiz["level"]]),
                    404
                );
            }
        }
        $subjectQuiz = QuizSubject::where("quiz_id", $quiz["id"])->first();
        $createTakenQuiz = TakenQuiz::create([
            "uuid" => Str::orderedUuid()->getHex(),
            "score" => 0,
            "status" => "active",
            "subject_quiz_id" => $subjectQuiz["id"],
            "user_id" => $userID,
        ]);


        return sendSuccRes(
            [
                "message" => transResMessage("created", ["path" => "Takenquiz"]),
                "data" => [
                    "Takequiz" => $createTakenQuiz,
                ],
            ],
            201
        );
    }
}
