<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
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
        $request->start = microtime(true);
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $request->end = microtime(true);
        $this->log($request,$response);
    }

    protected function log($request, $response)
    {
        $duration = round($request->end - $request->start, 5);
        $url = $request->fullUrl();
        $method = $request->getMethod();
        $ip = $request->getClientIp();
        $requestContent = $request->getContent();
        $responseContent = json_encode($response->getData(), JSON_PRETTY_PRINT);
        $status = $response->status();
        $statusText = $response->statusText();
        $log = "{$ip}: {$method}@{$url} - {$duration}ms \n".
            "Request : {$requestContent} \n".
            "Response {$status} {$statusText}: {$responseContent} \n";
        Log::channel('requests')->info($log);
    }
}
