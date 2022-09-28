<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResException;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizSubject;
use App\Models\TakenQuizAnswer;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class TakenQuizAnswerController extends Controller
{
    //
    public function submitAnswer(Request $request, $lang, $questionId)
    {
        $body = $request->validate([
            "userAnswerId" => "",
        ]);

        // Get current user
        $user = Auth::user();

        // Get qustion
        $question = Question::where("uuid", $questionId)->first();

        // Get his active quiz
        $currentActiveQuiz = $user->takenQuizzes->where("status", "active");

        if (!$currentActiveQuiz) {
            throw new ErrorResException("there is no active quiz
            ");
        }

        // Check for more active quizzes or for no current active quizzes
        if ($currentActiveQuiz->count() > 1) {
            throw new ErrorResException("There is more than 1 active quiz");
        }
        if ($currentActiveQuiz->count() < 1) {
            throw new ErrorResException("There is now active quiz");
        }

        // Get first index
        $currentActiveQuiz = $currentActiveQuiz->first();

        // If answer is empty it means that user didnt submit an answer in time
        if (is_null($body["userAnswerId"])) {
            $submittedAnswer = TakenQuizAnswer::create([
                "uuid" => Str::orderedUuid()->getHex(),
                "is_correct" => false,
                "user_id" => $user->id,
                "correct_answer_id" => null,
                "user_answer_id" => null,
                "taken_quiz_id" => $currentActiveQuiz->id,
                "question_id" => $question->id,
            ]);

            return [
                "success" => true,
            ];
        }

        // Get user answer
        $userAnswer = Answer::where("uuid", $body["userAnswerId"])->first();

        //****** Check if question duration is expired *******/
        $currentDate = new DateTime(date("Y-m-d H:i:s"));
        $currentDate = $currentDate->getTimestamp();

        $expireDate = null;
        // Check if its the first submitted answer
        if (is_null($currentActiveQuiz->last_submitted_answer)) {
            $expireDate = new DateTime($currentActiveQuiz->created_at);
        } else {
            $expireDate = new DateTime(
                $currentActiveQuiz->last_submitted_answer
            );
        }

        // Add question duration
        $expireDate->modify(
            "+" . getMinutesCustomFormat($question->duration) . " minutes"
        );

        $expireDate = $expireDate->getTimestamp();

        // Result will return -1 if $currentDate is bigger than $expireDate
        // which means that question date is expired
        $result = $expireDate - $currentDate;
        // dd($expireDate . " " . $currentDate);

        if ($result < 0) {
            throw new ErrorResException("qustion is expired");
        }

        // Get the corret answer of the qustion
        $correctAnswer = (int) $question->answers
            ->where("isCorrect", true)
            ->first()->id;

        $submittedAnswer = TakenQuizAnswer::create([
            "uuid" => Str::orderedUuid()->getHex(),
            "is_correct" => $userAnswer === $correctAnswer ? true : false,
            "user_id" => $user->id,
            "correct_answer_id" => (int) $correctAnswer,
            "user_answer_id" => $userAnswer->id,
            "taken_quiz_id" => $currentActiveQuiz->id,
            "question_id" => $question->id,
        ]);

        // Set last submitted answer
        $currentActiveQuiz->last_submitted_answer = date("Y-m-d H:i:s");
        $currentActiveQuiz->save();

        // Get total user answers to the taken quiz
        $answerNo = $currentActiveQuiz->takenQuizAnswers->count();
        // Get the total questions number of the taken quiz
        $questionNo = $currentActiveQuiz->subjectQuiz->questions->count();

        // Check if it was the last answer
        if ($answerNo === $questionNo) {
            // Count total correct user's answers
            $correctUserAnswers = $currentActiveQuiz->takenQuizAnswers
                ->where("is_correct", 1)
                ->count();

            // Count result
            $result = round(($correctUserAnswers / $questionNo) * 100);

            $message =
                $result < 60
                    ? "Ops, You have failed to pass the quiz, your quiz result is:"
                    : "Congrats: You have pass the quiz, Your quiz result is:";

            $currentActiveQuiz->status = $result < 60 ? "failed" : "passed";
            $currentActiveQuiz->score = $result;
            $currentActiveQuiz->save();

            // Calculating user's rank
            $totalQuizzes = QuizSubject::all()->count();

            $userPassedQuizzes = $user->takenQuizzes
                ->where("status", "passed")
                ->count();
            $dbUser = User::where("id", $user->id)->first();
            $rank = floor((($userPassedQuizzes / $totalQuizzes) * 100) / 4);

            $dbUser->rank = $rank == 0 ? 1 : $rank;
            $dbUser->save();

            return [
                "success" => true,
                "message" => $message . " " . $result . "%",
            ];
        }

        return sendSuccRes(["data" => $submittedAnswer], 201);
    }
}
