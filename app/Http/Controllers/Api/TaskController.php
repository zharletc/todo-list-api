<?php

namespace App\Http\Controllers\Api;

use App\Exports\TaskExport;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Resources\TaskResources;
use App\Http\Services\TaskService;
use App\Models\Task;
use Illuminate\Http\Request;
use Validator;

class TaskController extends ApiController
{
    public function index(TaskService $taskService)
    {
        $validator = Validator::make(request()->all(), [
            'title' => ['nullable', 'string'],
            'assignee' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:pending,open,in_progress,completed'],
            'priority' => ['nullable', 'string', 'in:low,medium,high'],
            'start' => ['nullable', 'date'],
            'end' => ['nullable', 'date', 'after_or_equal:start'],
            'min' => ['nullable', 'numeric', 'min:0'],
            'max' => ['nullable', 'numeric', 'gte:min'],
            'download' => ['nullable', 'boolean'],
        ]);

        $query = $validator->validated();
        $tasks = $taskService->getTask($query);
        return $this->buildResponse(true, 'success', TaskResources::collection($tasks));
    }

    public function store(TaskStoreRequest $request, TaskService $taskService)
    {
        $input = $request->validated();
        $task = $taskService->createTask($input);
        return $this->buildResponse(true, 'success', new TaskResources($task), 201);
    }

    public function show(string $id)
    {
        //
    }

    public function update(TaskStoreRequest $request, Task $task, TaskService $taskService)
    {
        $input = $request->validated();
        $task = $taskService->updateTask($task, $input);
        return $this->buildResponse(true, 'success update', new TaskResources($task), 200);
    }

    public function destroy(string $id)
    {
        //
    }

    public function chart(TaskService $taskService)
    {
        $validator = Validator::make(request()->all(), [
            'type' => ['required', 'string', 'in:priority,status,assignee'],
        ]);

        $query = $validator->validated();
        $type = $query['type'];
        $result = $taskService->chartTask($type);
        return $this->buildResponse(true, 'success', $result);
    }
}
