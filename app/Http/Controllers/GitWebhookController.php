<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GitWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $providedSecret = $request->input('secret_key');

        // Проверка секретного ключа
        if ($providedSecret !== env('GIT_WEBHOOK_SECRET')) {
            return response()->json(['message' => 'Invalid secret key'], 403);
        }

        // Проверка блокировки
        if (Storage::disk('local')->exists('git-webhook.lock')) {
            return response()->json(['message' => 'Update already in progress'], 423);
        }

        // Создание файла блокировки
        Storage::disk('local')->put('git-webhook.lock', 'locked');

        try {
            // Логирование запроса
            WebhookLog::create([
                'date' => now(),
                'ip' => $request->ip(),
                'message' => 'Webhook received'
            ]);

            // Выполнение команд Git с записью ошибок
            $commands = [
                'git checkout main',
                'git fetch origin main',
                'git reset --hard origin/main',
                'git pull origin main'
            ];

            foreach ($commands as $command) {
                $output = [];
                $returnVar = 0;
                exec($command . ' 2>&1', $output, $returnVar);
                if ($returnVar !== 0) {
                    throw new \Exception('Git command failed: ' . implode("\n", $output));
                }

                // Логирование результата команды
                WebhookLog::create([
                    'date' => now(),
                    'ip' => $request->ip(),
                    'message' => 'Executed: ' . $command . ' Output: ' . implode("\n", $output)
                ]);
            }

            // Удаление файла блокировки
            Storage::disk('local')->delete('git-webhook.lock');

            return response()->json(['message' => 'Update completed successfully']);
        } catch (\Exception $e) {
            // Удаление файла блокировки при ошибке
            Storage::disk('local')->delete('git-webhook.lock');

            // Логирование ошибки
            WebhookLog::create([
                'date' => now(),
                'ip' => $request->ip(),
                'message' => 'Error: ' . $e->getMessage()
            ]);

            return response()->json(['message' => 'Update failed', 'error' => $e->getMessage()], 500);
        }
    }

}

