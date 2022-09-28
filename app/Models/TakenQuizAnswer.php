<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakenQuizAnswer extends Model
{
    use HasFactory;

    public $table = "taken_quiz_answer";

    protected $fillable = [
        "uuid",
        "user_id",
        "is_correct",
        "question_id",
        "correct_answer_id",
        "user_answer_id",
        "taken_quiz_id",
        "created_at",
        "updated_at",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function correctAnswer()
    {
        return $this->belongsTo(Answer::class, "correct_answer_id");
    }

    public function userAnswer()
    {
        return $this->belongsTo(Answer::class, "user_answer_id");
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function takenQuiz()
    {
        return $this->belongsTo(TakenQuiz::class, "taken_quiz_id");
    }
}
