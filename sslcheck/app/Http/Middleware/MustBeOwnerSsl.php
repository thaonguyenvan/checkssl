<?php

namespace App\Http\Middleware;

use Closure;
use App\Ssl;
use Illuminate\Support\Facades\Auth;

class MustBeOwnerSsl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
        public function handle($request, Closure $next)
        {
            $id = $request->id;

            $ssl = Ssl::find($id);

            if($ssl->user_id == Auth::user()->id)
            {
                return $next($request);
            } else {
                return redirect()->route('myssl');
            }
        }
}