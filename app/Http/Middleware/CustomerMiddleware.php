<?php

namespace App\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
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
        if (Auth::guard('customer')->check() && auth('customer')->user()->is_active) {
            return $next($request);
        }
        if (Auth::guard('customer')->check()) {
            auth()->guard('customer')->logout();
            if ($request->expectsJson()) {
                return response()->json(['error' => translate('the_account_is_suspended')], 403);
            }
            Toastr::warning(translate('the_account_is_suspended'));
            return redirect()->route('customer.auth.login');
        }
        if ($request->expectsJson()) {
            return response()->json(['error' => translate('login_first_for_next_steps')], 401); // Return 401 status code for unauthenticated
        }

        Toastr::info(translate('login_first_for_next_steps'));
        return redirect()->route('customer.auth.login');
    }

}
