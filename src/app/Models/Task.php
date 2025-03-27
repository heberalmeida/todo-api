<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'due_date'];

    protected $casts = [
        'status'    => TaskStatus::class,
        'due_date'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
