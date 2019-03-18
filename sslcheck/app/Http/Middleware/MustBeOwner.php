<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;

class MustBeOwner
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

        $user = User::find($id);

        if($user->id == Auth::user()->id)
        {
            return $next($request);
        } else {
            return redirect()->route('profile');
        }
    }
}
