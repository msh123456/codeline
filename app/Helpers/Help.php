<?php

/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 7/2/2019
 * Time: 12:48 PM
 */

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class Help
{
  public static function success($data = null, $status = 200)
  {
    return response()->json(['status' => 'success', 'data' => $data], $status);
  }

  public static function error($messages = null, $status = 422)
  {
    return response()->json(['status' => 'error', 'messages' => $messages], $status);
  }

  public static function requireScope($scopeName)
  {
    $user = Auth::user();
    if (!$user)
      return Help::error(["msg" => "Forbidden"], 403);
    $scopes = $user->scopes;
    $scopes = explode(",", $scopes);
    foreach ($scopes as $scope) {
      if ($scope == $scopeName)
        return true;
    }
    return Help::error(["msg" => "Forbidden"], 403);
  }

}