<?php

namespace App\Http\Middleware;

use Closure;
use App\Domain;
use Illuminate\Support\Facades\Auth;

class mustbeownerdomain
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

            $domain = Domain::find($id);

            if($domain->user_id == Auth::user()->id)
            {
                return $next($request);
            } else {
                return redirect()->route('mydomain');
            }
    }
}
