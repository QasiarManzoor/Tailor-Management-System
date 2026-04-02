<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'action' => trim((string) $request->string('action')),
            'user_id' => $request->integer('user_id') ?: null,
        ];

        $logs = ActivityLog::with('user')
            ->when($filters['action'] !== '', fn ($query) => $query->where('action', 'like', '%'.$filters['action'].'%'))
            ->when($filters['user_id'], fn ($query) => $query->where('user_id', $filters['user_id']))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.activity-logs.index', [
            'logs' => $logs,
            'filters' => $filters,
            'users' => User::orderBy('name')->get(),
        ]);
    }
}
