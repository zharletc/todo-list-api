<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Task;

class TaskChartTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        // Buat data dummy
        Task::factory()->create([
            'title' => 'Task A',
            'assignee' => 'ogi',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => now(),
            'time_tracked' => 2,
        ]);

        Task::factory()->create([
            'title' => 'Task B',
            'assignee' => 'ogi',
            'status' => 'completed',
            'priority' => 'low',
            'due_date' => now(),
            'time_tracked' => 5,
        ]);
    }

    public function test_priority_chart_returns_expected_structure()
    {
        $response = $this->getJson('/api/task-charts?type=priority');

        $response->assertOk();
        $response->assertJsonStructure(['data' => ['priority_summary']]);

        $data = $response->json('data.priority_summary');
        $this->assertArrayHasKey('high', $data);
        $this->assertArrayHasKey('low', $data);
    }

    public function test_status_chart_returns_expected_structure()
    {
        $response = $this->getJson('/api/task-charts?type=status');

        $response->assertOk();
        $response->assertJsonStructure(['data' => ['status_summary']]);

        $data = $response->json('data.status_summary');
        $this->assertArrayHasKey('pending', $data);
        $this->assertArrayHasKey('completed', $data);
    }

    public function test_assignee_chart_returns_expected_structure()
    {
        $response = $this->getJson('/api/task-charts?type=assignee');

        $response->assertOk();
        $response->assertJsonStructure(['data' => ['assignee_summary']]);

        $summary = $response->json('data.assignee_summary');
        $this->assertArrayHasKey('ogi', $summary);

        $this->assertEquals(2, $summary['ogi']['total_todos']);
        $this->assertEquals(1, $summary['ogi']['total_pending_todos']);
        $this->assertEquals(5, $summary['ogi']['total_timetracked_completed_todos']);
    }
}
