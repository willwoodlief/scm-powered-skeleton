<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response|never-return
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && (auth()->user()->can_log_in === User::USER_STATUS_SUSPENDED)){
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Your Account cannot login, please contact Admin.');

        }

        return $next($request);
    }
}
