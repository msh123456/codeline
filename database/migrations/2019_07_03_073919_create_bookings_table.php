<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('bookings', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('room_id');
        $table->date('date_start');
        $table->date('date_end');
        $table->string('user_name',127);
        $table->string('user_email',255);
        $table->integer('total_nights',false,true);
        $table->decimal('total_price');
        $table->unsignedBigInteger('user_id')->nullable();
        $table->timestamps();

        $table->foreign("room_id")->references("id")->on("rooms")->onDelete("restrict")->onUpdate("cascade");



      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
