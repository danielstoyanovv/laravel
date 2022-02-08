<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsCrewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights_crew', function (Blueprint $table) {
            $table->increments('id');
            $table->string('main_captain')->nullable();
            $table->string('captain')->nullable();
            $table->string('crew_member_1')->nullable();
            $table->string('crew_member_2')->nullable();
            $table->string('crew_member_3')->nullable();
            $table->foreignId('flight_id');
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
        Schema::dropIfExists('flights_crew');
    }
}
