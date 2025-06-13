<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskFilterTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        Task::factory()->create([
            'title' => 'Tugas A',
            'assignee' => 'ogi',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => '2025-06-15',
            'time_tracked' => 2,
        ]);

        Task::factory()->create([
            'title' => 'Tugas B',
            'assignee' => 'eko',
            'status' => 'completed',
            'priority' => 'low',
            'due_date' => '2025-06-20',
            'time_tracked' => 5,
        ]);
    }

    public function test_can_filter_by_title()
    {
        $response = $this->getJson('/api/tasks?title=Tugas A');
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['title' => 'Tugas A']);
    }

    public function test_can_filter_by_assignee_multiple()
    {
        $response = $this->getJson('/api/tasks?assignee=ogi,eko');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    public function test_can_filter_by_time_tracked_range()
    {
        $response = $this->getJson('/api/tasks?min=3&max=6');

        $response->assertOk();
        $response->assertJsonCount(1,'data');
        $response->assertJsonFragment(['title' => 'Tugas B']);
    }

    public function test_invalid_date_fails_validation()
    {
        $response = $this->getJson('/api/tasks?start=invalid-date');
        $response->assertStatus(422);
    }
}
