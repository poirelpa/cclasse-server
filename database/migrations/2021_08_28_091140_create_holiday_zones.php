<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidayZones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holiday_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('academies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('holiday_zone_id')->constrained('holiday_zones')->onDelete('restrict');
            $table->timestamps();
        });
        Schema::create('public_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->string('name');
            $table->foreignId('holiday_zone_id')->constrained('holiday_zones')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('school_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('starts_on');
            $table->date('ends_on');
            $table->string('name');
            $table->foreignId('academy_id')->constrained('academies')->onDelete('cascade');
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
        Schema::dropIfExists('holiday_zones');
    }
}
