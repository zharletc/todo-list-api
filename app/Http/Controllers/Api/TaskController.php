<?php

namespace App\Http\Controllers\Api;

use App\Exports\TaskExport;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Resources\TaskResources;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends ApiController
{
    public function index()
    {
        $tasks = Task::orderBy('created_at', 'desc')->get();
        if (request()->download) {
            $data = new TaskExport($tasks);
            return $data->download('tasks.xlsx');
        }

        return $this->buildResponse(true, 'success', TaskResources::collection($tasks));
    }

    public function store(TaskStoreRequest $request)
    {
        $input = $request->validated();
        $task = Task::create($input);
        return $this->buildResponse(true, 'success', $task, 201);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
