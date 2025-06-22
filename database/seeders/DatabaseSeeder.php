<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Task;
use App\Models\Report;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Technicians manually
        $technicians = collect();

        for ($i = 1; $i <= 3; $i++) {
            $technicians->push(User::create([
                'name' => 'Technician ' . $i,
                'email' => 'tech' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'technician',
            ]));
        }

        // Create Tasks for each technician
        $tasks = [];
        foreach ($technicians as $technician) {
            $task = Task::create([
                'title' => 'Check Equipment ' . Str::random(5),
                'description' => 'Routine maintenance task.',
                'assigned_to' => $technician->id,
                'due_date' => Carbon::now()->addDays(rand(1, 5)),
                'status' => 'pending',
                'latitude' => -6.2 + rand(-50, 50) / 1000,
                'longitude' => 106.8 + rand(-50, 50) / 1000,
            ]);

            $tasks[] = $task;
        }

        // Create Reports for each task
        foreach ($tasks as $task) {
            Report::create([
                'task_id' => $task->id,
                'technician_id' => $task->assigned_to,
                'status' => rand(0, 1) ? 'completed' : 'failed',
                'notes' => 'Report submitted for task ID: ' . $task->id,
                'photo_path' => 'photos/task_' . $task->id . '.jpg',
                'gps_lat' => $task->latitude + rand(-10, 10) / 1000,
                'gps_lng' => $task->longitude + rand(-10, 10) / 1000,
                'submitted_at' => Carbon::now(),
            ]);
        }
    }
}
