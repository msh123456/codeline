<?php

namespace App\Http\Controllers\v1;

use App\Helpers\Help;
use App\RoomType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoomTypeController extends Controller
{
  public function add(Request $request)
  {
    $validator = Validator::make($request->all(),[
        "name"=>"required|max:127|unique:room_types,name"
    ]);
    if ($validator->fails())
      return Help::error($validator->errors());

    $roomType = new RoomType;
    $roomType->name = $request->get("name");
    $roomType->save();
    return Help::success();

  }


  public function edit(Request $request,$name)
  {
    $validator = Validator::make($request->all(),[
        "new_name"=>"required|max:127|unique:room_types,name,".$name.",name",
    ]);
    if ($validator->fails())
      return Help::error($validator->errors());

    $roomType = RoomType::find($name);
    if ($roomType==null)
      return Help::error(["msg"=>"RoomType Not Found!"],404);

    $roomType->name = $request->get("new_name");
    $roomType->save();
    return Help::success();

  }

  public function delete(Request $request,$name)
  {
    $roomType = RoomType::find($name);
    if ($roomType==null)
      return Help::error(["msg"=>"RoomType not found"],404);

    $roomType->delete();
    return Help::success();

  }

  public function get($name)
  {
    $roomType = RoomType::find($name);
    if ($roomType==null)
      return Help::error(["msg"=>"RoomType not found"],404);
    return Help::success($roomType);
  }

  public function getAll()
  {
    $roomTypes = RoomType::all();
    return Help::success($roomTypes);
  }
}
