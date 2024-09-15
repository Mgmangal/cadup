<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
class TimeZoneConverter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Your existing logic to authenticate the request
        $response = $next($request);
        // Retrieve the timezone from session
        $timezone = session('timezone', 'UTC'); 
        // Set the application timezone using Carbon
        Carbon::setToStringFormat('Y-m-d H:i:s');
        Carbon::now()->setTimezone($timezone);
        config(['app.timezone' => $timezone]);

        return $response;
    }
}