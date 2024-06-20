<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfoController extends Controller
{
    public function serverInfo()
    {
        return response()->json(['php_version' => phpversion()]);
    }

    public function clientInfo(Request $request)
    {
        return response()->json([
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);
    }

    public function databaseInfo()
    {
        $databaseName = DB::connection()->getDatabaseName();
        $version = DB::select("SELECT VERSION() AS version");
        $version = $version[0]->version;

        $connection = DB::connection()->getConfig();

        return response()->json([
            'database' => $databaseName,
            'version' => $version,
            'connection' => [
                'driver' => $connection['driver'],
                'host' => $connection['host'],
                'port' => $connection['port'],
                'database' => $connection['database'],
                'username' => $connection['username'],
            ],
        ]);
    }

}
