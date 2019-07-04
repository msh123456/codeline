<?php

namespace App\Http\Controllers\v1;

use App\Helpers\Help;
use App\Hotel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
  public function get(Request $request,$id)
  {
    $validator = Validator::make(["id"=>$id],[
        "id"=>'required|exists:hotels,id'
    ]);
    if ($validator->fails()){
      return Help::error($validator->errors());
    }

    $hotel = Hotel::find($id);
    return Help::success($hotel);

  }

  public function edit(Request $request,$id)
  {
    $validator = Validator::make($request->all(),[
        "name"=>'required|max:255'
        ,"address"=>'required|max:255'
        ,"city"=>'required|max:127'
        ,"state"=>'required|max:127'
        ,"country"=>'required|max:127'
        ,"zip_code"=>'required|numeric|min:3|max:31'
        ,"phone_number"=>'required|numeric|max:20'
        ,"email"=>'required|email|max:127'
        ,"image"=>'required|file|mimes:jpg,jpeg,bmp,png|min:128|max:1024'
    ]);
    if ($validator->fails()){
      return Help::error($validator->errors());
    }

    $file = $request->file('image');
    $destinationPath = env("uploadImagePath","uploads");
    $fileName = md5(time().rand(0,99999999).uniqid()).".".$file->clientExtension();
    $imageUrl = asset($destinationPath."/".$fileName);
    $file->move($destinationPath,$fileName);

    $hotel = Hotel::find($id);
    if ($hotel==null)
      return Help::error(["msg"=>"Hotel not found!"]);
    $hotel->name = $request->get("name");
    $hotel->address = $request->get("address");
    $hotel->city = $request->get("city");
    $hotel->state = $request->get("state");
    $hotel->country = $request->get("country");
    $hotel->zip_code = $request->get("zip_code");
    $hotel->phone_number = $request->get("phone_number");
    $hotel->email = $request->get("email");
    $hotel->image = $imageUrl;
    $hotel->save();
    return Help::success();
  }
}
