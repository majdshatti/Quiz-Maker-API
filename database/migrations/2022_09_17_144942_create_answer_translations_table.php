<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_translation', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->string("paragraph");
            $table->foreignId('answer_id')->constrained('answer')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('language')->onDelete('cascade');
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
        Schema::dropIfExists('answer_translation');
    }
};
