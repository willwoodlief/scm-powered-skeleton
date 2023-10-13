<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ScmAccounting
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
        if (Auth::user() ) {
            /**
             * @var User $user;
             */
            $user = Auth::user();
            if ($user->has_role(UserRole::USER_ROLE_ACCOUNTING)) {
                return $next($request);
            }

        }

         abort('403',__('Accounting User Only'));
    }
}
