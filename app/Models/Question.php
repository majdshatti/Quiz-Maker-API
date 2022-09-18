<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // Sql Table
    public $table = "question";

    // Mass Assigned Fields
    protected $fillable = [
        "uuid",
        "quiz_subject_id",
        "created_at",
        "updated_at",
    ];

    // Hidden Fields
    protected $hidden = ["id"];

    public function translations()
    {
        return $this->hasMany(QuestionTranslation::class);
    }

    public function quizSubject()
    {
        return $this->belongsTo(QuizSubject::class);
    }

    /**
     * Sorts a collection on request params if passed, using asc/desc orders
     *
     * @param query   $query
     * @param Request $request
     */
    public function scopeSort($query, $request)
    {
        sortFilter($query, $request);
    }

    /**
     *  Filters model's data based on request params
     *
     * @param query $query
     * @param array $filters Array that contains fields to be filtered
     *
     */
    public function scopeFilter($query, array $filters)
    {
        // Search Filter
        searchFilter(
            $query,
            "translations",
            ["paragraph"],
            $filters["search"] ?? false
        );
    }
}
