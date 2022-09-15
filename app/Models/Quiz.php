<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    public $table = "quiz";

    // Mass assigned fields
    protected $fillable = [
        "uuid",
        "slug",
    ];

    // Hidden fields
    protected $hidden = ["id"];

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
        stringFilter($query, "slug", $filters["slug"] ?? false);
    }
}
