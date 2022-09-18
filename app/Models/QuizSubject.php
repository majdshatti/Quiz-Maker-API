<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSubject extends Model
{
    use HasFactory;

    public $table = "quiz_subject";

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
