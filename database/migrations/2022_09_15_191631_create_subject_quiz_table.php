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
        Schema::create("quiz_subject", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("quiz_id");
            $table->unsignedBigInteger("subject_id");
            $table
                ->foreign("quiz_id")
                ->references("id")
                ->on("quiz");
            $table
                ->foreign("subject_id")
                ->references("id")
                ->on("subject");
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
        Schema::dropIfExists("quiz_subject");
    }
};
