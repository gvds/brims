<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetUserTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty(auth()->user())) {
            // session value set on login
            // dd(session('team_id'));
            // setPermissionsTeamId(session('team_id'));
            setPermissionsTeamId(auth()->user()->team_id);
        }
        return $next($request);
    }
}
