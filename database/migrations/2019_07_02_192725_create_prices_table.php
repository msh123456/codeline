<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('prices', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string("room_type_name",127);
        $table->unsignedBigInteger("hotel_id");
        $table->text("description")->nullable();
        $table->decimal("price");
        $table->timestamps();

        $table->unique(['room_type_name', 'hotel_id']);

        $table->foreign("room_type_name")->references("name")->on("room_types")->onDelete("restrict")->onUpdate("cascade");
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
        Schema::dropIfExists('prices');
    }
}
