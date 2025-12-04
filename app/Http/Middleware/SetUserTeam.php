<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $response = $next($request);
        if (!empty(Auth::user()) && session()->has('currentProject')) {
            setPermissionsTeamId(session('currentProject')->id);
        }

        if (!empty(Auth::user())) {
        Auth::user()->unsetRelation('roles')->unsetRelation('permissions');
        // return $next($request);
        return $response;
    }
}
