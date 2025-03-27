<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskRepository
{
    public function all()
    {
        return Task::where('user_id', Auth::id())->latest()->paginate(10);
    }

    public function find($id): ?Task
    {
        return Task::where('user_id', Auth::id())->findOrFail($id);
    }

    public function create(array $data): Task
    {
        return Auth::user()->tasks()->create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
