<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_dashboard_uses_forwarded_https_for_vite_assets(): void
    {
        $response = $this->withServerVariables([
            'REMOTE_ADDR' => '10.0.0.1',
            'HTTP_HOST' => 'daymark.example.test',
            'HTTP_X_FORWARDED_PROTO' => 'https',
        ])->get('/tasks');

        $response->assertSee('href="https://localhost:8000/build/assets/app-', false);
    }

    public function test_create_and_edit_forms_render_with_navigation_counts(): void
    {
        $task = Task::factory()->create(['task_name' => 'Plan the week']);

        $this->get(route('tasks.create'))
            ->assertSee('Add a task')
            ->assertSee('All tasks');

        $this->get(route('tasks.edit', $task))
            ->assertSee('Edit task')
            ->assertSee('Plan the week')
            ->assertSee('All tasks');
    }

    public function test_dashboard_counts_and_marks_only_past_due_pending_tasks_as_overdue(): void
    {
        $this->travelTo('2026-09-25 12:00:00');
        Task::factory()->create([
            'task_name' => 'Late report',
            'status' => 'pending',
            'due_date' => '2026-09-24',
        ]);
        Task::factory()->create([
            'task_name' => 'Completed report',
            'status' => 'completed',
            'due_date' => '2026-09-24',
        ]);
        Task::factory()->create([
            'task_name' => 'Due today',
            'status' => 'pending',
            'due_date' => '2026-09-25',
        ]);
        Task::factory()->create([
            'task_name' => '<script>alert(1)</script>',
            'status' => 'pending',
            'due_date' => '2026-09-26',
        ]);

        $response = $this->get(route('tasks.index', ['filter' => 'overdue']));

        $response->assertSee('Late report')
            ->assertDontSee('Completed report')
            ->assertDontSee('Due today');

        $dashboard = $this->get(route('tasks.index'));
        $dashboard->assertSee('data-overdue="true"', false)
            ->assertSee('data-overdue="false"', false)
            ->assertSee('<script>alert(1)</script>');
    }

    public function test_valid_task_is_created_and_redirects_to_dashboard(): void
    {
        $response = $this->post(route('tasks.store'), [
            'task_name' => 'Plan the week',
            'description' => 'Review priorities',
            'status' => 'pending',
            'due_date' => '2026-10-01',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'task_name' => 'Plan the week',
            'description' => 'Review priorities',
            'status' => 'pending',
            'due_date' => '2026-10-01 00:00:00',
        ]);
    }

    public function test_task_name_is_required_and_invalid_tasks_are_not_created(): void
    {
        $response = $this->from(route('tasks.create'))->post(route('tasks.store'), [
            'task_name' => '',
            'status' => 'pending',
        ]);

        $response->assertSessionHasErrors(['task_name']);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_task_can_be_updated(): void
    {
        $task = Task::factory()->create(['task_name' => 'Draft notes']);

        $response = $this->put(route('tasks.update', $task), [
            'task_name' => 'Finish notes',
            'description' => 'Send to the team',
            'status' => 'pending',
            'due_date' => '2026-10-05',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'task_name' => 'Finish notes',
            'description' => 'Send to the team',
            'due_date' => '2026-10-05 00:00:00',
        ]);
    }

    public function test_task_status_can_be_changed_to_completed(): void
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $response = $this->patch(route('tasks.status', $task), ['status' => 'completed']);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'completed']);
    }

    public function test_task_can_be_deleted(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
