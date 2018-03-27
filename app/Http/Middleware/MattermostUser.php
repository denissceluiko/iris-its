<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class MattermostUser
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
        if ($request->has('user_id') && $request->has('user_name'))
        {
            $user = User::where('mm_id', $request->user_id)->first();
            if (!$user) {
                $user = User::create([
                    'mm_id' => $request->user_id,
                    'name' => $request->user_name,
                ]);
            }
            Auth::login($user);
        }
        else
        {
            return response()->json('Unauthorized', 401);
        }
        return $next($request);
    }
}
