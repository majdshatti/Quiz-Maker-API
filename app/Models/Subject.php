<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    // Sql Table
    public $table = "subject";

    // Mass Assigned Fields
    protected $fillable = ["uuid", "slug", "created_at", "updated_at"];

    // Hidden Fields
    protected $hidden = ["id"];

    // Get data including relations
    protected $with = ["translations"];

    // Subject Translation Relation
    public function translations()
    {
        return $this->hasMany(SubjectTranslation::class);
    }

    // Subject Quiz relation
    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, "quiz_subject");
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
        stringFilter($query, "slug", $filters["slug"] ?? false);
        // Relation filtering
        relationFilter(
            $query,
            "translations",
            "name",
            $filters["name"] ?? false
        );
        // Search Filter
        searchFilter(
            $query,
            "translations",
            ["name", "description", "slug"],
            $filters["search"] ?? false
        );
    }
}
