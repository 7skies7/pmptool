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
        $flag = false;
        $allowedGateways = ['116.50', '49.38', '173.39.121', '72.163.217'];
        // $allowedGatewaysThree = ['173.39.121', '72.163.217'];
        $clientIPArr = explode('.', request()->ip());
        $clientIPTwo = $clientIPArr[0].'.'.$clientIPArr[1];
        $clientIPThree = $clientIPArr[0].'.'.$clientIPArr[1].'.'.$clientIPArr[2];
        if (in_array($clientIPTwo, $allowedGateways)){
            // here instead of checking a single ip address we can do collection of ips
            //address in constant file and check with in_array function
            $flag = true;
        }
        if (in_array($clientIPThree, $allowedGateways)){
            // here instead of checking a single ip address we can do collection of ips
            //address in constant file and check with in_array function
            $flag = true;
        }
        if ($flag == false){
            abort('403');
        }

        return $next($request);
    }
}
