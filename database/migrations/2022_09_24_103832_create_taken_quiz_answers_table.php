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
        Schema::create("taken_quiz_answer", function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->unsignedBigInteger("user_answer_id")->nullable();
            $table->unsignedBigInteger("correct_answer_id")->nullable();
            $table->boolean("is_correct");
            $table
                ->foreignId("user_id")
                ->constrained("user")
                ->onDelete("cascade");
            $table
                ->foreignId("question_id")
                ->constrained("question")
                ->onDelete("cascade");
            $table
                ->foreignId("taken_quiz_id")
                ->constrained("taken_quiz")
                ->onDelete("cascade");
            $table
                ->foreign("user_answer_id")
                ->references("id")
                ->on("answer");
            $table
                ->foreign("correct_answer_id")
                ->references("id")
                ->on("answer");

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
        Schema::dropIfExists("taken_quiz_answer");
    }
};
