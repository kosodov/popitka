<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSecretKey extends Command
{
    protected $signature = 'generate:secret-key';
    protected $description = 'Generate a 36-character secret key';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $secretKey = Str::random(36);
        $this->info('Your 36-character secret key: ' . $secretKey);
    }
}
