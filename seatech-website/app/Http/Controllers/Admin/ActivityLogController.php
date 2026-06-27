<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Super Admin');
    }

    public function index(Request $request)
    {
        $query = Activity::with('causer', 'subject')
            ->latest();

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->paginate(25)->withQueryString();

        $logNames = Activity::select('log_name')
            ->whereNotNull('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name')
            ->all();

        $users = User::orderBy('name')->get(['id', 'name']);

        return view('admin.activity-log.index', [
            'activities' => $activities,
            'logNames' => $logNames,
            'users' => $users,
            'filters' => $request->only(['log_name', 'causer_id', 'event', 'date_from', 'date_to']),
        ]);
    }

    public function show(Activity $activity)
    {
        $activity->load('causer', 'subject');

        return view('admin.activity-log.show', [
            'activity' => $activity,
        ]);
    }
}
