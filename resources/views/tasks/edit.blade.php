@extends('layouts.app')

@section('title', 'Edit task')

@section('content')
    <section class="mx-auto max-w-2xl rise-in">
        <a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-[#768077] hover:text-[#1d2b24]">← Back to tasks</a>
        <div class="mb-7 mt-6">
            <p class="text-sm font-semibold text-[#75816f]">Make an update</p>
            <h1 class="mt-1 font-serif text-3xl text-[#1d2b24]">Edit task</h1>
        </div>
        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-5 rounded-lg border border-[#e3e6de] bg-white p-5 sm:p-7">
            @csrf
            @method('PUT')
            @include('tasks._form')
        </form>
    </section>
@endsection