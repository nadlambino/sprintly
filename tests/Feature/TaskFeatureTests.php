<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\Status;

class TaskFeatureTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_restore_a_task()
    {
        $task = Task::factory()->create(['deleted_at' => now()]);

        $this->putJson(route('api.tasks.restore', $task->id))
             ->assertOk();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_can_transition_a_task()
    {
        $task = Task::factory()->create();
        $status = Status::factory()->create();

        $this->putJson(route('api.tasks.transition', $task->id), [
                'status_id' => $status->id,
            ])
            ->assertOk();

        $this->assertEquals($task->fresh()->status_id, $status->id);
    }

    /** @test */
    public function it_can_list_task_parents()
    {
        Task::factory()->count(3)->create();

        $this->getJson(route('api.tasks.parents'))
            ->assertOk()
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_get_task_metrics()
    {
        Task::factory()->count(5)->create();

        $this->getJson(route('api.tasks.metrics'))
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /** @test */
    public function it_can_perform_crud_on_tasks()
    {
        // Create
        $taskData = ['name' => 'New Task'];
        $this->postJson(route('api.tasks.store'), $taskData)
            ->assertCreated();

        // Read
        $this->getJson(route('api.tasks.index'))
            ->assertOk();

        // Update
        $task = Task::first();
        $this->putJson(route('api.tasks.update', $task->id), ['name' => 'Updated Task'])
            ->assertOk();

        // Delete
        $this->deleteJson(route('api.tasks.destroy', $task->id))
            ->assertNoContent();
    }
}