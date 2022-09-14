<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "user";

    // Mass assigned fields
    protected $fillable = [
        "uuid",
        "name",
        "slug",
        "email",
        "password",
        "email_verfied_at",
        "remember_token",
        "email_verified_at",
    ];

    // Hidden fields
    protected $hidden = ["id", "password", "remember_token"];

    protected $casts = [
        "email_verified_at" => "datetime",
    ];

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
        stringFilter($query, "name", $filters["name"] ?? false);
        stringFilter($query, "email", $filters["email"] ?? false);
        // Number filtering
        numberFilter($query, $filters["rank"] ?? false);
    }
}
