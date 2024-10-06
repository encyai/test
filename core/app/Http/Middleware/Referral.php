<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Referral {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $general = gs();
        if (!$general->referral) {
            $notify[] = ['warning', 'The referral system is currently disabled'];
            return redirect()->route('user.home')->withNotify($notify);
        }
        return $next($request);
    }
}
