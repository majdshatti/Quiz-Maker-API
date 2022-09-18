<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionTranslation extends Model
{
    use HasFactory;

    public $table = "question_translation";

    // Mass assigned fields
    protected $fillable = ["uuid", "paragraph", "question_id", "language_id"];

    // Hidden fields
    protected $hidden = ["id"];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Language Relation
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
