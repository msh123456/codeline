<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',127);
            $table->unsignedBigInteger('hotel_id');
            $table->string('room_type',127);
            $table->string('image',511);
            $table->timestamps();


        $table->foreign("room_type")->references("name")->on("room_types")->onDelete("restrict")->onUpdate("cascade");

          $table->foreign("hotel_id")->references("id")->on("hotels")->onDelete("restrict")->onUpdate("cascade");




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
