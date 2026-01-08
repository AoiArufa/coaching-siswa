<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::latest()
            ->with('user')
            ->paginate(20);

        return view('admin.activity-log', compact('logs'));
    }
}
