<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tasks') · Daymark</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f3f4ef] font-sans text-[#1d2b24] antialiased">
    <div class="min-h-screen md:grid md:grid-cols-[248px_minmax(0,1fr)]">
        <aside class="hidden border-r border-[#e2e5dd] bg-white px-5 py-7 md:flex md:flex-col">
            <a href="{{ route('tasks.index') }}" class="mb-12 flex items-center gap-3 px-2 text-lg font-bold tracking-normal text-[#1d2b24]">
                <span class="flex size-9 items-center justify-center rounded-lg bg-[#c9ed72] text-sm font-bold">TM</span>
                TaskManager
            </a>
            <p class="mb-3 px-3 text-[11px] font-bold uppercase text-[#929a91]">Workspace</p>
            <nav class="flex flex-col gap-1" aria-label="Task filters">
                @foreach ([['all', 'All tasks', $stats['total']], ['pending', 'To do', $stats['pending']], ['overdue', 'Overdue', $stats['overdue']], ['completed', 'Completed', $stats['completed']]] as [$key, $label, $count])
                    <a href="{{ route('tasks.index', ['filter' => $key]) }}" class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium {{ $filter === $key ? 'bg-[#eff3e8] text-[#1d2b24]' : 'text-[#68736b] hover:bg-[#f5f6f2] hover:text-[#1d2b24]' }}">
                        <span class="flex items-center gap-3">
                            <span class="size-1.5 rounded-full {{ $key === 'overdue' ? 'bg-[#dc7758]' : ($key === 'completed' ? 'bg-[#8a9b84]' : 'bg-[#a1b880]') }}"></span>
                            {{ $label }}
                        </span>
                        <span class="text-xs tabular-nums text-[#929a91]">{{ $count }}</span>
                    </a>
                @endforeach
            </nav>
            <div class="mt-auto rounded-lg bg-[#f4f6f0] p-4">
                <p class="text-sm font-semibold">One thing at a time.</p>
                <p class="mt-1 text-xs leading-5 text-[#707a70]">Keep your day moving with a short, clear list.</p>
            </div>
        </aside>

        <div class="min-w-0">
            <header class="flex items-center justify-between border-b border-[#e2e5dd] bg-white px-5 py-4 md:hidden">
                <a href="{{ route('tasks.index') }}" class="flex items-center gap-2.5 font-bold">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-[#c9ed72] text-xs">D.</span>
                    Daymark
                </a>
                <a href="{{ route('tasks.create') }}" class="rounded-lg bg-[#1d2b24] px-3.5 py-2 text-sm font-semibold text-white">New task</a>
            </header>
            <nav class="flex gap-2 overflow-x-auto border-b border-[#e2e5dd] bg-white px-5 py-3 md:hidden" aria-label="Task filters">
                @foreach ([['all', 'All'], ['pending', 'To do'], ['overdue', 'Overdue'], ['completed', 'Done']] as [$key, $label])
                    <a href="{{ route('tasks.index', ['filter' => $key]) }}" class="shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold {{ $filter === $key ? 'bg-[#eff3e8] text-[#1d2b24]' : 'text-[#68736b]' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <main class="mx-auto w-full max-w-[1180px] px-5 py-7 sm:px-8 sm:py-10 lg:px-12">
                <div class="mb-8 hidden items-center justify-between md:flex">
                    <span class="text-sm text-[#778077]">Personal task manager <span class="px-1.5 text-[#c3c8bf]">/</span> Tasks</span>
                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#1d2b24] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#314439] focus:outline-none focus:ring-2 focus:ring-[#80955a] focus:ring-offset-2">
                        <span aria-hidden="true" class="text-lg leading-none">+</span> New task
                    </a>
                </div>
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>