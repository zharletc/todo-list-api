<?php

namespace App\Http\Services;

use App\Exports\TaskExport;
use App\Models\Task;

class TaskService
{
    public function getTask($query = [])
    {
        $tasks = Task::query();

        if (!empty($query['title'])) {
            $tasks->where('title', 'like', '%' . $query['title'] . '%');
        }

        if (!empty($query['assignee'])) {
            $assignee = explode(',', $query['assignee']);
            $tasks->whereIn('assignee', $assignee);
        }

        if (!empty($query['status'])) {
            $status = explode(',', $query['status']);
            $tasks->whereIn('status', $status);
        }

        if (!empty($query['priority'])) {
            $priority = explode(',', $query['priority']);
            $tasks->whereIn('priority', $priority);
        }

        if (!empty($query['start'])) {
            $tasks->where('due_date', '>=', $query['start']);
        }

        if (!empty($query['end'])) {
            $tasks->where('due_date', '<=', $query['end']);
        }

        if (isset($query['min'])) {
            $tasks->where('time_tracked', '>=', $query['min']);
        }

        if (isset($query['max'])) {
            $tasks->where('time_tracked', '<=', $query['max']);
        }

        $tasks = $tasks->orderBy('created_at', 'desc')->get();

        if (isset($query['download'])) {
            $data = new TaskExport($tasks);
            return $data->download('tasks.xlsx');
        }

        return $tasks;
    }

    public function createTask($data)
    {
        return Task::create($data);
    }

    public function updateTask(Task $task, $data)
    {
        $task->update($data);
        $task = Task::find($task->id);
        return $task;
    }

    public function chartTask($type)
    {
        $response = [];
        if ($type == 'priority') {
            // SHOW ALL WITH ZERO COUNT
            $allPriorities = Task::ALL_PRIORITY;
            $data = Task::select('priority', \DB::raw('count(*) as total'))
                ->groupBy('priority')
                ->pluck('total', 'priority')
                ->toArray();

            $chart = collect($allPriorities)->mapWithKeys(function ($priority) use ($data) {
                return [$priority => $data[$priority] ?? 0];
            });

            // JUST SHOW EXISITING PRIORITY
            // $chart = Task::select('priority', \DB::raw('count(*) as total'))->groupBy('priority')->get();
            $response = [
                'priority_summary' => $chart
            ];

        } else if ($type == 'status') {
            // SHOW ALL WITH ZERO COUNT
            $allStatus = Task::ALL_STATUS;
            $data = Task::select('status', \DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $chart = collect($allStatus)->mapWithKeys(function ($status) use ($data) {
                return [$status => $data[$status] ?? 0];
            });

            // JUST SHOW EXISITING STATUS
            // $chart = Task::select('status', \DB::raw('count(*) as total'))->groupBy('status')->get();
            $response = [
                'status_summary' => $chart
            ];
        } else if ($type == 'assignee') {
            $assignees = Task::select(
                'assignee',
                \DB::raw('COUNT(*) as total_todos'),
                \DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as total_pending_todos"),
                \DB::raw("SUM(CASE WHEN status = 'completed' THEN time_tracked ELSE 0 END) as total_timetracked_completed_todos")
            )
                ->groupBy('assignee')
                ->get();

            $chart = $assignees->mapWithKeys(function ($item) {
                $assignee = $item->assignee ?? 'N/A';
                return [
                    $assignee => [
                        'total_todos' => (int) $item->total_todos,
                        'total_pending_todos' => (int) $item->total_pending_todos,
                        'total_timetracked_completed_todos' => (int) $item->total_timetracked_completed_todos,
                    ],
                ];
            });
            $response = [
                'assignee_summary' => $chart
            ];
        }

        return $response;
    }
}
