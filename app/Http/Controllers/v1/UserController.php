<?php

namespace App\Http\Controllers\v1;

use App\Helpers\Help;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class UserController extends Controller
{
  /**
   * Register a new user
   */
  public function register(Request $request)
  {
    $v = Validator::make($request->all(), [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'password'  => 'required|min:3',
        'c_password'  => 'required|min:3|same:password',
    ]);
    if ($v->fails())
    {
      return Help::error($v->errors());
    }
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->save();
    return Help::success();
  }
  /**
   * Login user and return a token
   */
  public function login(Request $request)
  {
    $v = Validator::make($request->all(), [

        'email' => 'required|email',
        'password'  => '',

    ]);
    if ($v->fails())
    {
      return Help::error($v->errors());
    }
    $credentials = $request->only('email', 'password');
    if ($accessToken = $this->guard()->attempt($credentials)) {
      return Help::success(["access_token"=>$accessToken,"expire_time"=>time()+env("JWT_TTL",60)*60]);
    }
    return Help::error(['msg'=>'Username or password is incorrect'],401);
  }
  /**
   * Logout User
   */
  public function logout()
  {
    $this->guard()->logout();
    return Help::success();
  }
  /**
   * Get authenticated user
   */
  public function info(Request $request)
  {
    $user = User::find(Auth::user()->id);
    return Help::success($user);
  }
  /**
   * Refresh JWT token
   */
  public function refresh()
  {
    try {
      if ($token = $this->guard()->refresh()) {
        return Help::success(["access_token" => $token, "expire_time" => time() + env("JWT_TTL", 60) * 60]);
      }
    }catch (TokenBlacklistedException $exception){
      return Help::error(["msg"=>$exception->getMessage()]);
    }
    return Help::success(['msg' => 'refresh_token_error'], 401);
  }
  /**
   * Return auth guard
   */
  private function guard()
  {
    return Auth::guard();
  }
}
