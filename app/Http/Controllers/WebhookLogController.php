<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebhookLog;

class WebhookLogController extends Controller
{
    public function index()
    {
        $logs = WebhookLog::all();
        return view('webhook_logs.index', compact('logs'));
    }

}
