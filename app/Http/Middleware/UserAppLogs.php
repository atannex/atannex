<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserAppLogs
{
     /**
      * Handle an incoming request.
      *
      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
      * @param  \Illuminate\Http\Request  $request
      * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
      * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
      */
     public function handle(Request $request, Closure $next): Response
     {
          $path = $request->path();

          if (Auth::check()) {
               addUserActivity(Auth::user()->id, $path);
          } else {
               addUserActivity(0, $path);
          }

          return $next($request);
     }
}
