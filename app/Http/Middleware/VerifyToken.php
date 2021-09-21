<?php

namespace App\Http\Middleware;

use Closure;

class VerifyToken
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
        $header = $request->header('token-key');
        $encryptedkey = base64_encode(date("dmY").env('APP_KEY')); 
        if ($header == $encryptedkey) {
            return $next($request);           
        }              
        return response()->json(array('status' => false, 'message' => 'Unauthorized'), 413);
    }
}
