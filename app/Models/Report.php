<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'task_id', 'technician_id', 'status', 'notes', 'photo_path', 'gps_lat', 'gps_lng', 'submitted_at'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
