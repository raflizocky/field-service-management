<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'due_date',
        'status',
        'latitude',
        'longitude'
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }
}
