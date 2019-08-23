<?php

namespace App\Http\Middleware;

use Closure;

class IpMiddleware
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
        $allowedGateways = ['116.50', '49.38', '127.0'];
        $clientIPArr = explode('.', request()->ip());
        $clientIP = $clientIPArr[0].'.'.$clientIPArr[1];
        if (!in_array($clientIP, $allowedGateways)) {
        // here instead of checking a single ip address we can do collection of ips
        //address in constant file and check with in_array function
            abort('403');
        }

        return $next($request);
    }
}
