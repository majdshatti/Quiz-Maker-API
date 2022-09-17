<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizTranslation extends Model
{
    use HasFactory;

    public $table = "quiz_translation";

    // Mass assigned fields
    protected $fillable = [
        "uuid",
        "name",
        "description",
        "quiz_id",
        "language_id",
        "created_at",
        "updated_at",
    ];

    // Hidden fields
    protected $hidden = ["id"];

    // Quiz Relation
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Language Relation
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
