<?php

namespace App\Http\Middleware;
use App\Helpers\Help;
use Closure;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
  // Override handle method
  public function handle($request, Closure $next, ...$guards)
  {
    if ($this->authenticate($request, $guards) === 'authentication_failed') {
      return Help::error(["msg"=>"Unauthorized"],400);
    }
    return $next($request);
  }
  // Override authentication method
  protected function authenticate($request, array $guards)
  {
    if (empty($guards)) {
      $guards = [null];
    }
    foreach ($guards as $guard) {
      if ($this->auth->guard($guard)->check()) {
        return $this->auth->shouldUse($guard);
      }
    }
    return 'authentication_failed';
  }
}
