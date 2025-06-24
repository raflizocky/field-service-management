<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function today(Request $request)
    {
        $tasks = Task::where('assigned_to', $request->user()->id)
            ->whereDate('due_date', Carbon::today())
            ->get();

        return response()->json([
            'tasks' => $tasks
        ]);
    }
}
