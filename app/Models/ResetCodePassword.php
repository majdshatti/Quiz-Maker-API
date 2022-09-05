<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetCodePassword extends Model
{
    use HasFactory;

    public $table = "password_resets";

    protected $fillable = ["email", "code", "created_at","updated_at"];
}
