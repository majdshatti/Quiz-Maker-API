<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerTranslation extends Model
{
    use HasFactory;

    public $table = "answer_translation";

    // Mass assigned fields
    protected $fillable = [
        "uuid",
        "paragraph",
        "answer_id",
        "language_id",
        "created_at",
        "updated_at",
    ];

    // Hidden fields
    protected $hidden = ["id"];

    // Answer Relation
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    // Language Relation
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
