<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogRequest;
use App\DTOs\LogRequestDTO;
use App\DTOs\LogRequestCollectionDTO;
use Illuminate\Support\Facades\Auth;

class LogRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $logs = LogRequest::query();

        if ($request->has('filter')) {
            foreach ($request->filter as $filter) {
                $logs->where($filter['key'], $filter['value']);
            }
        }

        if ($request->has('sortBy')) {
            foreach ($request->sortBy as $sort) {
                $logs->orderBy($sort['key'], $sort['order']);
            }
        }

        $logs = $logs->paginate($request->input('count', 10));

        return new LogRequestCollectionDTO($logs);
    }

    public function show($id)
    {
        $log = LogRequest::findOrFail($id);

        return new LogRequestDTO($log);
    }

    public function destroy($id)
    {
        $log = LogRequest::findOrFail($id);
        $log->delete();

        return response()->json(['message' => 'Log deleted successfully']);
    }
}
