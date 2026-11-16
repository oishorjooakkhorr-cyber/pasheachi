<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lets a request through only if the logged-in user has one of the given
 * roles; everyone else gets 403 Forbidden.
 *
 * Used in routes as 'role:client', 'role:runner', 'role:admin', or with
 * several roles, e.g. 'role:client,admin'. Put 'auth' before it so guests
 * are sent to the login page first.
 */
class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $allowed = array_map(fn (string $role): UserRole => UserRole::from($role), $roles);

        abort_unless($request->user()?->hasRole(...$allowed), 403);

        return $next($request);
    }
}
