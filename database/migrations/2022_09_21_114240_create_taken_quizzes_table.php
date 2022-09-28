<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("taken_quiz", function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->float("score");
            $table->string("status");
            $table
                ->foreignId("subject_quiz_id")
                ->constrained("quiz_subject")
                ->onDelete("cascade");
            $table
                ->foreignId("user_id")
                ->constrained("user")
                ->onDelete("cascade");
            $table->date("last_submitted_answer")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("taken_quiz");
    }
};
