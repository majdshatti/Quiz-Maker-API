<?php

namespace App\Http\Controllers;

use App\Models\TakenQuiz;
use Illuminate\Http\Request;

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
    //* @route:  POST /api/subject/{subjectSlug}/quiz/{quizSlug}
    //* @access: `USER`

    function takeQuiz($request, $subjectSlug, $quizSlug)
    {
    }
}
