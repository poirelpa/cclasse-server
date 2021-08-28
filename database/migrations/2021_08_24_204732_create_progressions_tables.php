<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progressions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('starts_on');
            $table->text('description');
            $table->smallInteger('duration')->unsigned();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('progressions')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('progression_subject', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('order_index')->unsigned();
            $table->foreignId('progression_id')->constrained('progressions')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
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
        Schema::dropIfExists('progression_subject');
        Schema::dropIfExists('progressions');
    }
}
