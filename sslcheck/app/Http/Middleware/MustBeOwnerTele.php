<?php

namespace App\Http\Middleware;

use Closure;
use App\Tele_noti;
use Illuminate\Support\Facades\Auth;

class MustBeOwnerTele
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

            $tele_noti = Tele_noti::find($id);

            if($tele_noti->user_id == Auth::user()->id)
            {
                return $next($request);
            } else {
                return redirect()->route('setting');
            }
    }
}
