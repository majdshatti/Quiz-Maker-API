<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    public $table = "language";
    protected $fillable = ["uuid", "name", "code"];
    protected $hidden = ["id"];

    // Subject Translation Relation
    public function subjectTranslations()
    {
        return $this->hasMany(SubjectTranslation::class);
    }
}
