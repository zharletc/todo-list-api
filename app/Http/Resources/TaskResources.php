<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'assignee' => $this->assignee,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'priority' => $this->priority,
            'time_tracked' => $this->time_tracked,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,

        ];
    }
}
