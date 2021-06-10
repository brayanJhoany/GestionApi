<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Psr7\Header;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //en el * se ingresan las url de donde permitiremos el aceso a la api
        $handle = $next($request);
        if (method_exists($handle, 'header')) {
            $handle->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST,PUT, DELETE, PATCH, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept, X-Requested-With, Application');
        }

        return $handle;
    }
}
