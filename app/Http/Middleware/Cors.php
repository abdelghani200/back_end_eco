<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $response = $next($request);
        // // $response->headers->set('Access-Control-Allow-Credentials', 'true');
        // $response->headers->set('Access-Control-Allow-Credentials', 'true');
        // $response->headers->set('Access-Control-Allow-Origin', '*');
        // $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        // $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application', 'ip');

        // $response = $next($request);
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:5173');
        // $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        // $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Api-Key, Accept, Authorization, X-Requested-With, Application');
        // $response->headers->set('Access-Control-Allow-Credentials', 'true');
        return $response;
        

        // return $response;

        // return $next($request);
    }
}
