<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTranslation extends Model
{
    use HasFactory;

    // Sql Table
    public $table = "subject_translation";

    // Mass Assigned fields
    protected $fillable = [
        "uuid",
        "slug",
        "name",
        "description",
        "language_id",
        "subject_id",
        "created_at",
        "updated_at",
    ];

    // Hidden fields
    protected $hidden = ["id", "language_id", "subject_id"];

    // Subject Relation
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Language Relation
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
