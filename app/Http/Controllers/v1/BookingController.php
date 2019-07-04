<?php

namespace App\Http\Controllers\v1;

use App\Booking;
use App\Helpers\Help;
use App\Price;
use App\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
  public function add(Request $request)
  {
    $validator = Validator::make($request->all(), [
        "room_id" => 'required|exists:rooms,id'
      , "date_start" => 'required|date_format:Y-m-d|after:today'
      , "date_end" => 'required|date_format:Y-m-d|after:date_start'
      , "user_name" => 'required|max:127'
      , "user_email" => 'required|email|max:255'
      , "user_id" => 'nullable|numeric|exists:users,id'
    ]);
    if ($validator->fails()) {
      return Help::error($validator->errors());
    }
    $date_start = $request->get("date_start");
    $date_end = $request->get("date_end");


    $whereSql = "(room_id='".$request->get('room_id')."') and (
    date_start between '$date_start' and '$date_end' or 
    date_end between '$date_start' and '$date_end' or 
    '$date_start' between date_start and date_end or 
    '$date_end' between date_end and date_end )";

    $dateConflictCount = Booking::whereRaw($whereSql)->count();
    if ($dateConflictCount>0)
      return Help::error(["msg"=>"room has been reserved before between these dates."]);

    $price = Room::find($request->get("room_id"))->getPrice();
    if ($price)
      $price=$price->price;
    else
      return Help::error(["msg"=>"price not defined. Please define the price."]);

  //calculate total nights
    $carbonDateStart = Carbon::parse($date_start);
    $carbonDateEnd = Carbon::parse($date_end);
    $totalNights = $carbonDateStart->diffInDays($carbonDateEnd);

//calculate price
    $totalPrice = bcmul($price,$totalNights,2);

//insert new record to DB
    $booking = new Booking;
    $booking->room_id = $request->get("room_id");
    $booking->date_start = $request->get("date_start");
    $booking->date_end = $request->get("date_end");
    $booking->user_name = $request->get("user_name");
    $booking->user_email = $request->get("user_email");
    $booking->total_price = $totalPrice;
    $booking->total_nights = $totalNights;
    $booking->user_id = $request->get("user_id");
    $booking->save();
    return Help::success($booking);


  }

  public function edit(Request $request,$id)
  {
    $validator = Validator::make($request->all(), [
        "room_id" => 'required|exists:rooms,id'
      , "date_start" => 'required|date_format:Y-m-d|after:today'
      , "date_end" => 'required|date_format:Y-m-d|after:date_start'
      , "user_name" => 'required|max:127'
      , "user_email" => 'required|email|max:255'
      , "user_id" => 'nullable|numeric|exists:users,id'
    ]);
    if ($validator->fails()) {
      return Help::error($validator->errors());
    }
    $booking = Booking::find($id);
    if (!$booking)
      return Help::error(["msg"=>"Record not found"],404);
    $date_start = $request->get("date_start");
    $date_end = $request->get("date_end");


    $whereSql = "(room_id='".$request->get('room_id')."') and id!=$id and (
    date_start between '$date_start' and '$date_end' or 
    date_end between '$date_start' and '$date_end' or 
    '$date_start' between date_start and date_end or 
    '$date_end' between date_end and date_end )";

    $dateConflictCount = Booking::whereRaw($whereSql)->count();
    if ($dateConflictCount>0)
      return Help::error(["msg"=>"room has been reserved before between these dates."]);

    $price = Room::find($request->get("room_id"))->getPrice();
    if ($price)
      $price=$price->price;
    else
      return Help::error(["msg"=>"price not defined. Please define the price."]);

    //calculate total nights
    $carbonDateStart = Carbon::parse($date_start);
    $carbonDateEnd = Carbon::parse($date_end);
    $totalNights = $carbonDateStart->diffInDays($carbonDateEnd);

//calculate price
    $totalPrice = bcmul($price,$totalNights,2);

//update record
    $booking->room_id = $request->get("room_id");
    $booking->date_start = $request->get("date_start");
    $booking->date_end = $request->get("date_end");
    $booking->user_name = $request->get("user_name");
    $booking->user_email = $request->get("user_email");
    $booking->total_price = $totalPrice;
    $booking->total_nights = $totalNights;
    $booking->user_id = $request->get("user_id");
    $booking->save();
    return Help::success($booking);
  }

  public function get(Request $request,$id)
  {
    $booking = Booking::find($id);
    if(!$booking)
     return Help::error(["msg"=>"Booking Not Found!"],404);
    return Help::success($booking);
  }
  public function getAll(Request $request)
  {
    $bookings = Booking::all();
    return Help::success($bookings);
  }

  public function delete(Request $request,$id)
  {
    $booking = Booking::find($id);
    if(!$booking)
      return Help::error(["msg"=>"Booking Not Found!"],404);
    $booking->delete();
    return Help::success();
  }
}
