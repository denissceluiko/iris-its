<?php

namespace App\Http\Middleware\Mattermost;

use Closure;

class Token
{
    /**
     * Handles Mattermost token checking
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('token') && $request->has('team_domain'))
        {
            $token = Token::find($request->token);

            if ($token && $token->team()->mm_domain == $request->team_domain)
            {
                return $next($request);
            }
        }
        return response()->setStatusCode(403);
    }
}
