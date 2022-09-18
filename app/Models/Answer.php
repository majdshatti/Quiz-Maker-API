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
        "isCorrect",
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
        // String filtering
        stringFilter($query, "uuid", $filters["answerId"] ?? false);
        // Relation filtering
        relationFilter(
            $query,
            "translations",
            "uuid",
            $filters["questionId"] ?? false
        );
        // Search Filter
        searchFilter(
            $query,
            "translations",
            ["name", "paragraph"],
            $filters["search"] ?? false
        );
    }
    
}
