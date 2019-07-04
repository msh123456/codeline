<?php

namespace App\Http\Controllers\v1;

use App\Helpers\Help;
use App\Price;
use App\Room;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mockery\Exception;

class PriceController extends Controller
{
  public function add(Request $request)
  {
    $validator = Validator::make($request->all(), [
        "room_type_name" => "required|exists:room_types,name",
        "hotel_id" => "required|exists:hotels,id",
        "price" => "required|numeric|min:0.01"
    ]);
    if ($validator->fails())
      return Help::error($validator->errors());

    $price = new Price;
    $price->room_type_name = $request->get("room_type_name");
    $price->hotel_id = $request->get("hotel_id");
    $price->price = $request->get("price");
    $price->description = $request->get("description");
    try {
      $price->save();
    } catch (QueryException $exception) {
      if ($exception->getCode() == 23000)
        return Help::error("Record exists.");
      else
        return Help::error($exception->getMessage());
    }
    return Help::success();


  }


  public function edit(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
        "room_type_name" => "required|exists:room_types,name",
        "hotel_id" => "required|exists:hotels,id",
        "price" => "required|numeric|min:0.01"
    ]);
    if ($validator->fails())
      return Help::error($validator->errors());

    $price = Price::find($id);
    if ($price == null)
      return Help::error(["msg" => "Record not found"], 404);

    $price->room_type_name = $request->get("room_type_name");
    $price->hotel_id = $request->get("hotel_id");
    $price->price = $request->get("price");
    $price->description = $request->get("description");;
    try {
      $price->save();
    } catch (QueryException $exception) {
      if ($exception->getCode() == 23000)
        return Help::error("Record exists.");
      else
        return Help::error($exception->getMessage());
    }
    return Help::success();

  }

  public function delete(Request $request, $id)
  {
    $price = Price::find($id);
    if ($price == null)
      return Help::error(["msg" => "Record not found"], 404);

    $price->delete();
    return Help::success();

  }

  public function get($id)
  {
    $price = Price::find($id);
    if ($price == null)
      return Help::error(["msg" => "Record not found"], 404);
    return Help::success($price);
  }

  public function getAll()
  {
    $prices = Price::all();
    return Help::success($prices);
  }
}
