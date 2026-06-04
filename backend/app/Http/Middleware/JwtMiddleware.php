<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return $this->unauthorized('User not found.');
            }
        } catch (TokenExpiredException) {
            return $this->unauthorized('Token has expired.');
        } catch (TokenInvalidException) {
            return $this->unauthorized('Token is invalid.');
        } catch (JWTException) {
            return $this->unauthorized('Token not provided.');
        }

        return $next($request);
    }
}
