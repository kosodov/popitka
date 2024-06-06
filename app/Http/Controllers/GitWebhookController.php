<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GitWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secretKey = env('GIT_WEBHOOK_SECRET');
        $inputKey = $request->input('secret_key');

        if (!$secretKey || $secretKey !== $inputKey) {
            return response()->json(['message' => 'Invalid secret key'], 403);
        }

        $lockFile = storage_path('app/git_update.lock');
        
        if (File::exists($lockFile)) {
            return response()->json(['message' => 'Update already in progress'], 423);
        }

        try {
            File::put($lockFile, 'locked');

            // Логирование даты и IP адреса
            Log::info('Git update initiated', ['date' => now(), 'ip' => $request->ip()]);

            // Переключение на главную ветку
            exec('git checkout main', $output, $status);
            Log::info('Git checkout main', ['status' => $status, 'output' => $output]);

            // Отмена всех изменений
            exec('git reset --hard HEAD', $output, $status);
            Log::info('Git reset', ['status' => $status, 'output' => $output]);

            // Обновление проекта с гита
            exec('git pull origin main', $output, $status);
            Log::info('Git pull', ['status' => $status, 'output' => $output]);

            return response()->json(['message' => 'Update completed successfully']);
        } catch (\Exception $e) {
            Log::error('Git update failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Update failed'], 500);
        } finally {
            File::delete($lockFile);
        }
    }
}

