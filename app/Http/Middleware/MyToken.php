<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //user id
        $user_id = $request->user_id;

        //find user
        $user = User::find($user_id);
        //check if user exist
        if(!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        //token user remember_token
        $token = $user->remember_token;

        if ($request->header('Authorization') !== $token) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        return $next($request);
    }
}
