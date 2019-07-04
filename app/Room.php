<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  public function getPrice()
  {
    return Price::where("hotel_id",$this->hotel_id)->where("room_type_name",$this->room_type)->first();
  }
}
