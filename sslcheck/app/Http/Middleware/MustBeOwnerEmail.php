<?php

namespace App\Http\Middleware;

use Closure;
use App\Email_noti;
use Illuminate\Support\Facades\Auth;

class MustBeOwnerEmail
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

            $email_noti = Email_noti::find($id);

            if($email_noti->user_id == Auth::user()->id)
            {
                return $next($request);
            } else {
                return redirect()->route('setting');
            }
        }
}
