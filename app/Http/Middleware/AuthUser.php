<?php

namespace App\Http\Middleware;

use App\Models\PersonalToken;
use Closure;
use Illuminate\Http\Request;

class AuthUser
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
        $token = $request->header('Authorization');
        $personalToken = PersonalToken::where('token', $token)->first();

        if ($personalToken) {
            return $next($request);
        } else {
            return response('Anda Tidak Memiliki Otoritas.', 401);
        }
    }
}
