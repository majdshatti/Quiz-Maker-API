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
        Schema::create("subject_translation", function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->unsignedBigInteger("language_id");
            $table->unsignedBigInteger("subject_id");
            $table->string("name");
            $table->string("description");
            $table
                ->foreign("language_id")
                ->references("id")
                ->on("language");
            $table
                ->foreign("subject_id")
                ->references("id")
                ->on("subject")
                ->onDelete("cascade");
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
        Schema::dropIfExists("subject_translation");
    }
};
