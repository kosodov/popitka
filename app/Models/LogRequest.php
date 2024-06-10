<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LogRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_url',
        'http_method',
        'controller_path',
        'controller_method',
        'request_body',
        'request_headers',
        'user_id',
        'ip_address',
        'user_agent',
        'status_code',
        'response_body',
        'response_headers',
        'called_at',
    ];

    protected $casts = [
        'called_at' => 'datetime',
        'request_headers' => 'array',
        'response_headers' => 'array',
    ];

    public $timestamps = false;

    // Scope for filtering logs older than 73 hours
    public function scopeOlderThan($query, $hours)
    {
        $limit = Carbon::now()->subHours($hours);
        return $query->where('called_at', '<', $limit);
    }
}
