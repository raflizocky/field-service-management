<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:completed,failed',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'gps_lat' => 'nullable|numeric',
            'gps_lng' => 'nullable|numeric',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
        }

        $report = Report::create([
            'task_id' => $request->task_id,
            'technician_id' => $request->user()->id,
            'status' => $request->status,
            'notes' => $request->notes,
            'photo_path' => $photoPath,
            'gps_lat' => $request->gps_lat,
            'gps_lng' => $request->gps_lng,
            'submitted_at' => now(),
        ]);

        return response()->json([
            'message' => 'Report submitted successfully',
            'report' => $report
        ]);
    }
}
