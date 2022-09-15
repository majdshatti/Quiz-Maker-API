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
    ];

    // Hidden fields
    protected $hidden = ["id"];
}
