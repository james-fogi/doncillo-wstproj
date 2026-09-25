<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $filters = ['all', 'pending', 'completed', 'overdue'];
        $filter = $request->query('filter', 'all');
        $filter = in_array($filter, $filters, true) ? $filter : 'all';

        $tasks = Task::query()
            ->when($filter === 'pending', fn ($query) => $query->where('status', 'pending'))
            ->when($filter === 'completed', fn ($query) => $query->where('status', 'completed'))
            ->when($filter === 'overdue', fn ($query) => $query
                ->where('status', 'pending')
                ->whereDate('due_date', '<', today()->toDateString()))
            ->orderBy('due_date')
            ->orderByDesc('created_at')
            ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'stats' => $this->taskStats(),
            'filter' => $filter,
        ]);
    }

    public function create(): View
    {
        return $this->formView('tasks.create', new Task);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'task_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:pending,completed'],
            'due_date' => ['nullable', 'date'],
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task added.');
    }

    public function edit(Task $task): View
    {
        return $this->formView('tasks.edit', $task);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'task_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:pending,completed'],
            'due_date' => ['nullable', 'date'],
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,completed'],
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task status updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    private function formView(string $view, Task $task): View
    {
        return view($view, [
            'task' => $task,
            'stats' => $this->taskStats(),
            'filter' => 'all',
        ]);
    }

    /** @return array<string, int> */
    private function taskStats(): array
    {
        return [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::where('status', 'pending')
                ->whereDate('due_date', '<', today()->toDateString())
                ->count(),
        ];
    }
}
