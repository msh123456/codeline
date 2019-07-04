<?php

namespace App\Http\Controllers\v1;

use App\Helpers\Help;
use App\Room;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
  public function add(Request $request)
  {
    $validator = Validator::make($request->all(), [
        "name" => "required|min:3|max:127",
        "hotel_id" => "required|exists:hotels,id",
        "room_type" => "required|exists:room_types,name",
        "image" => "required|file|mimes:jpg,jpeg,png|min:64|max:1024"
    ]);
    if ($validator->fails())
      return Help::error($validator->errors());

    $file = $request->file('image');
    $destinationPath = env("uploadImagePath", "uploads");
    $fileName = md5(time() . rand(0, 99999999) . uniqid()) . "." . $file->clientExtension();
    $imageUrl = asset($destinationPath . "/" . $fileName);
    $file->move($destinationPath, $fileName);

    $room = new Room;
    $room->name = $request->get("name");
    $room->hotel_id = $request->get("hotel_id");
    $room->room_type = $request->get("room_type");
    $room->image = $imageUrl;
    $room->save();
    return Help::success();

  }


  public function edit(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
        "name" => "required|min:3|max:127",
        "hotel_id" => "required|exists:hotels,id",
        "room_type" => "required|exists:room_types,name",
        "image" => "file|mimes:jpg,jpeg,png|min:64|max:1024"
    ]);
    if ($validator->fails())
      return Help::error($validator->errors());
    $room = Room::find($id);
    if ($room == null)
      return Help::error(["msg" => "Room not found"], 404);

    if (!empty($request->file('image'))) {
      $file = $request->file('image');
      $destinationPath = env("uploadImagePath", "uploads");
      $fileName = md5(time() . rand(0, 99999999) . uniqid()) . "." . $file->clientExtension();
      $room->image = asset($destinationPath . "/" . $fileName);
      $file->move($destinationPath, $fileName);
    }

    $room->name = $request->get("name");
    $room->hotel_id = $request->get("hotel_id");
    $room->room_type = $request->get("room_type");
    $room->save();
    return Help::success();

  }

  public function delete(Request $request, $id)
  {
    $room = Room::find($id);
    if ($room == null)
      return Help::error(["msg" => "Room not found"], 404);

    try {
      $room->delete();
    } catch (QueryException $exception) {
      if ($exception->getCode() == 23000)
        return Help::error(["msg" =>"Can not delete because of usage in other tables."]);
      else
        return Help::error($exception->getMessage());
    }
    return Help::success();

  }

  public function get($id)
  {
    $room = Room::find($id);
    if ($room == null)
      return Help::error(["msg" => "Room not found"], 404);
    return Help::success($room);
  }

  public function getAll()
  {
    $rooms = Room::all();
    return Help::success($rooms);
  }

}
