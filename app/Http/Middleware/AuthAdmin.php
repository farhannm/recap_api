<?php

namespace App\Http\Middleware;

use App\Models\PersonalToken;
use Closure;
use Illuminate\Http\Request;

class AuthAdmin
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
        if (!$token) {
            return response('Anda Tidak Memiliki Otoritas.', 401);
        }

        $personalToken = PersonalToken::where('token', $token)->with('user')->first();

        if ($personalToken) {
            $user = $personalToken->user;
            try {
                if ($user->role->id == 1) {
                    return $next($request);
                } else {
                    return response('Anda Tidak Memiliki Hak Akses.', 401);
                }
            } catch (\Exception $e) {
                return response('Anda Tidak Memiliki Hak Akses.', 401);
            }
        } else {     
            return response('Anda Tidak Memiliki Otoritas.', 401);
        }
    }
}
