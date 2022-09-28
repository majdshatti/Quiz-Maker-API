<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakenQuiz extends Model
{
    use HasFactory;

    public $table = "taken_quiz";

    // Mass assigned fields
    protected $fillable = [
        "uuid",
        "score",
        "status",
        "subject_quiz_id",
        "user_id",
        "created_at",
        "updated_at",
    ];

    // Hidden fields
    protected $hidden = ["id"];

    //Taken Quiz Answer Relation
    public function takenQuizAnswers()
    {
        return $this->hasMany(TakenQuizAnswer::class);
    }

    //User Relation
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Subject Quiz Relation
<<<<<<< HEAD
    public function subjectQuiz()
=======
    public function subjectQuizzes()
>>>>>>> 1ed0e235048c778e376eb792c41aaf9eba690305
    {
        return $this->belongsTo(QuizSubject::class);
    }

    //***************************************/
    //*************** SCOPES ****************/
    //***************************************/

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
        stringFilter($query, "status", $filters["status"] ?? false);

        // Relation filtering
        relationFilter($query, "user", "name", $filters["name"] ?? false);
        // Number filtering
        numberFilter($query, $filters["score"] ?? false);
        // Search Filter
        /*searchFilter(
            $query,
            "translations",
            ["score"],
            $filters["search"] ?? false
        );*/
    }
}
