<?php
namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware for user authentication with JWT checking
 */
class AuthenticateAPI extends BaseMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate(); //
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'message'   => 'Token is Invalid'
                ], 403);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'message'   => 'Token is Expired'
                ], 403);

            } else {
                return response()->json([
                    'message'   => 'Authorization Token not found'
                ], 401);
            }
        }

        return $next($request);
    }
}
