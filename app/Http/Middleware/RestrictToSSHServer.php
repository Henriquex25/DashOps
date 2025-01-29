<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class RestrictToSSHServer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = config('services.ssh_server.token');
        $hash  = $request->header('Authorization');

        throw_if(empty($token) || !Hash::check($token, $hash), new UnauthorizedException('Unauthorized.'));

        return $next($request);
    }
}
