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
        Schema::create("question_translation", function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->unsignedBigInteger("language_id");

            $table->string("paragraph");
            $table
                ->foreignId("question_id")
                ->constrained("question")
                ->onDelete("cascade");
            $table
                ->foreign("language_id")
                ->references("id")
                ->on("language");
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
        Schema::dropIfExists("question_translation");
    }
};
