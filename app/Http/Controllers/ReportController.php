<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::with(['task', 'technician'])->get();
        return response()->json(['reports' => $reports]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'technician_id' => 'required|exists:users,id',
            'notes' => 'required|string',
            'photo_path' => 'required|image|max:2048',
            'gps_lat' => 'required|numeric',
            'gps_lng' => 'required|numeric',
            'submitted_at' => 'required|date',
        ]);

        $report = Report::create($validated);
        return response()->json(['message' => 'Report created successfully', 'report' => $report], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $reports)
    {
        return response()->json(['report' => $reports->load(['task', 'technician'])]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $reports)
    {
        return view('reports.edit', compact('reports'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $reports)
    {
        $validated = $request->validate([
            'task_id' => 'nullable|exists:tasks,id',
            'technician_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'photo_path' => 'nullable|image|max:2048',
            'gps_lat' => 'nullable|numeric',
            'gps_lng' => 'nullable|numeric',
            'submitted_at' => 'nullable|date',
        ]);

        $reports->update($validated);
        return response()->json(['message' => 'Report updated successfully', 'report' => $reports]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $reports)
    {
        $reports->delete();
        return response()->json(['message' => 'Report deleted successfully']);
    }
}
