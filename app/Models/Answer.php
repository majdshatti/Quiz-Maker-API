<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    public $table = "answer";

    // Mass assigned fields
    protected $fillable = [
        "uuid",
        "question_id",
        "created_at",
        "updated_at",
    ];

    // Hidden fields
    protected $hidden = ["id"];

    public function translations()
    {
        return $this->hasMany(AnswerTranslation::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    
}
