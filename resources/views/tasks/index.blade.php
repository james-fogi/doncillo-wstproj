@extends('layouts.app')

@section('title', 'Your tasks')

@section('content')
    <section class="rise-in">
        <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="mb-2 text-sm font-semibold text-[#75816f]">{{ now()->format('l, F j') }}</p>
                <h1 class="font-serif text-3xl leading-tight text-[#1d2b24] sm:text-4xl">Make time for all the tasks.</h1>
                
            </div>
            <a href="{{ route('tasks.create') }}" class="inline-flex w-fit items-center gap-2 rounded-lg bg-[#1d2b24] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#314439] md:hidden">
                <span aria-hidden="true" class="text-lg leading-none">+</span> New task
            </a>
        </div>

        @if (session('success'))
            <div role="status" class="mb-5 rounded-lg border border-[#d7e3c7] bg-[#eef4e7] px-4 py-3 text-sm font-medium text-[#405a37]">{{ session('success') }}</div>
        @endif

        <div class="mb-8 grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach ([['All tasks', $stats['total'], 'text-[#1d2b24]'], ['To do', $stats['pending'], 'text-[#687d4b]'], ['Overdue', $stats['overdue'], 'text-[#c35d43]'], ['Completed', $stats['completed'], 'text-[#68736b]']] as [$label, $count, $color])
                <div class="rounded-lg border border-[#e3e6de] bg-white px-4 py-4 sm:px-5">
                    <p class="text-xs font-semibold text-[#879087]">{{ $label }}</p>
                    <p class="mt-2 text-2xl font-semibold tabular-nums {{ $color }}">{{ $count }}</p>
                </div>
            @endforeach
        </div>

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold">{{ match ($filter) { 'pending' => 'To do', 'completed' => 'Completed', 'overdue' => 'Overdue', default => 'All tasks' } }}</h2>
                <p class="mt-0.5 text-xs text-[#879087]">{{ $filter === 'overdue' ? 'Past-due tasks that still need your attention.' : 'Your tasks, deadlines, and progress.' }}</p>
            </div>
            <div class="hidden items-center gap-1 rounded-lg border border-[#e3e6de] bg-white p-1 sm:flex" aria-label="Task filter">
                @foreach ([['all', 'All'], ['pending', 'To do'], ['overdue', 'Overdue'], ['completed', 'Done']] as [$key, $label])
                    <a href="{{ route('tasks.index', ['filter' => $key]) }}" class="rounded-md px-3 py-1.5 text-xs font-semibold {{ $filter === $key ? 'bg-[#eff3e8] text-[#1d2b24]' : 'text-[#768077] hover:text-[#1d2b24]' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        @if ($tasks->isEmpty())
            <div class="rounded-lg border border-dashed border-[#d7ddd1] bg-white px-6 py-14 text-center">
                <span class="mx-auto mb-4 flex size-11 items-center justify-center rounded-full bg-[#eff3e8] text-xl text-[#70845b]" aria-hidden="true">+</span>
                <h3 class="font-semibold">{{ $filter === 'all' ? 'Nothing on your list yet' : 'No tasks in this view' }}</h3>
                <p class="mt-1 text-sm text-[#879087]">{{ $filter === 'all' ? 'Add a task to get your day started.' : 'Try another filter or add a new task.' }}</p>
                <a href="{{ route('tasks.create') }}" class="mt-5 inline-flex rounded-lg border border-[#dfe4da] px-3.5 py-2 text-sm font-semibold hover:bg-[#f7f8f4]">Add a task</a>
            </div>
        @else
            <div class="space-y-2.5">
                @foreach ($tasks as $task)
                    <article data-overdue="{{ $task->isOverdue() ? 'true' : 'false' }}" class="rise-in flex flex-col gap-4 rounded-lg border border-[#e3e6de] bg-white p-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                        <div class="flex min-w-0 items-start gap-3.5">
                            <form method="POST" action="{{ route('tasks.status', $task) }}" class="pt-0.5">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $task->status === 'completed' ? 'pending' : 'completed' }}">
                                <button type="submit" aria-label="{{ $task->status === 'completed' ? 'Reopen' : 'Complete' }} {{ $task->task_name }}" class="flex size-5 shrink-0 items-center justify-center rounded-full border {{ $task->status === 'completed' ? 'border-[#a9b99a] bg-[#a9b99a] text-white' : 'border-[#cbd2c7] text-transparent hover:border-[#85966d] hover:text-[#85966d]' }}">
                                    <span aria-hidden="true" class="text-xs">&#10003;</span>
                                </button>
                            </form>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="break-words text-sm font-semibold {{ $task->status === 'completed' ? 'text-[#929a91] line-through' : 'text-[#26352c]' }}">{{ $task->task_name }}</h3>
                                    @if ($task->status === 'completed')
                                        <span class="rounded-full bg-[#f0f2ee] px-2 py-0.5 text-[10px] font-bold text-[#7c877d]">Completed</span>
                                    @elseif ($task->isOverdue())
                                        <span class="rounded-full bg-[#fff0eb] px-2 py-0.5 text-[10px] font-bold text-[#c35d43]">Overdue</span>
                                    @else
                                        <span class="rounded-full bg-[#eff3e8] px-2 py-0.5 text-[10px] font-bold text-[#687d4b]">Pending</span>
                                    @endif
                                </div>
                                @if ($task->description)
                                    <p class="mt-1 max-w-2xl break-words text-sm leading-5 text-[#7a847b]">{{ $task->description }}</p>
                                @endif
                                <p class="mt-2 text-xs {{ $task->isOverdue() ? 'font-semibold text-[#c35d43]' : 'text-[#929a91]' }}">
                                    @if ($task->due_date)
                                        Due {{ $task->due_date->format('M j, Y') }}{{ $task->isOverdue() ? ' · past due' : '' }}
                                    @else
                                        No due date
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pl-8 sm:shrink-0 sm:pl-0">
                            <a href="{{ route('tasks.edit', $task) }}" class="rounded-md px-2.5 py-1.5 text-xs font-semibold text-[#68736b] hover:bg-[#f4f6f0] hover:text-[#1d2b24]">Edit</a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md px-2.5 py-1.5 text-xs font-semibold text-[#a06b5b] hover:bg-[#fff0eb] hover:text-[#a3442e]">Delete</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection