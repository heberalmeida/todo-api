<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskService
{
    public function __construct(
        protected TaskRepository $repository
    ) {}

    public function index()
    {
        return $this->repository->all();
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(Task $task, array $data)
    {
        return $this->repository->update($task, $data);
    }

    public function destroy(Task $task)
    {
        $this->repository->delete($task);
    }
}
