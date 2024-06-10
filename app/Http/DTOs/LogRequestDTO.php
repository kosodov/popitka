<?php

namespace App\DTOs;

use App\Models\LogRequest;

class LogRequestDTO
{
    public $id;
    public $full_url;
    public $http_method;
    public $controller;
    public $method;
    public $request_body;
    public $request_headers;
    public $user_id;
    public $ip_address;
    public $user_agent;
    public $status_code;
    public $response_body;
    public $response_headers;
    public $created_at;

    public function __construct(LogRequest $logRequest)
    {
        $this->id = $logRequest->id;
        $this->full_url = $logRequest->full_url;
        $this->http_method = $logRequest->http_method;
        $this->controller = $logRequest->controller;
        $this->method = $logRequest->method;
        $this->request_body = $logRequest->request_body;
        $this->request_headers = $logRequest->request_headers;
        $this->user_id = $logRequest->user_id;
        $this->ip_address = $logRequest->ip_address;
        $this->user_agent = $logRequest->user_agent;
        $this->status_code = $logRequest->status_code;
        $this->response_body = $logRequest->response_body;
        $this->response_headers = $logRequest->response_headers;
        $this->created_at = $logRequest->created_at;
    }
}


