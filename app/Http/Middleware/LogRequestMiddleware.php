<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LogRequest;
use Illuminate\Support\Facades\Auth;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $logRequest = new LogRequest();
        $logRequest->full_url = $request->fullUrl();
        $logRequest->http_method = $request->method();
        $logRequest->controller = $request->route()->getActionName();
        $logRequest->method = $request->route()->getActionMethod();
        $logRequest->request_body = $this->sanitizeData($request->getContent());
        $logRequest->request_headers = json_encode($request->headers->all());
        $logRequest->user_id = Auth::check() ? Auth::id() : null;
        $logRequest->ip_address = $request->ip();
        $logRequest->user_agent = $request->header('User-Agent');
        $logRequest->status_code = $response->status();
        $logRequest->response_body = $this->sanitizeData($response->getContent());
        $logRequest->response_headers = json_encode($response->headers->all());
        $logRequest->created_at = now();
        $logRequest->updated_at = now();
        $logRequest->save();

        return $response;
    }

    private function sanitizeData($data)
    {
        $patterns = [
            '/\"password\":\s*\".*?\"/i',
            '/\"token\":\s*\".*?\"/i',
        ];

        $replacements = [
            '"password": "*****"',
            '"token": "*****"',
        ];

        return preg_replace($patterns, $replacements, $data);
    }
}