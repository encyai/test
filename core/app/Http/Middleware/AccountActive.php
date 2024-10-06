<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccountActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user->activation) {
            $notify[] = ['warning', 'Please activate account first'];
            return redirect()->route('user.activate')->withNotify($notify);
        }
        return $next($request);
    }
}
