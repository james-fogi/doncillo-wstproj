<div>
    <label for="task_name" class="mb-1.5 block text-sm font-semibold">Task name</label>
    <input id="task_name" name="task_name" value="{{ old('task_name', $task->task_name) }}" required maxlength="255" autofocus class="w-full rounded-lg border border-[#dfe4da] bg-white px-3.5 py-2.5 text-sm outline-none transition placeholder:text-[#a1a89e] focus:border-[#84976a] focus:ring-2 focus:ring-[#84976a]/15" placeholder="What needs doing?">
    @error('task_name') <p class="mt-1.5 text-xs font-medium text-[#bd4e38]">{{ $message }}</p> @enderror
</div>

<div>
    <label for="description" class="mb-1.5 block text-sm font-semibold">Details <span class="font-normal text-[#929a91]">(optional)</span></label>
    <textarea id="description" name="description" rows="4" maxlength="2000" class="w-full resize-y rounded-lg border border-[#dfe4da] bg-white px-3.5 py-2.5 text-sm outline-none transition placeholder:text-[#a1a89e] focus:border-[#84976a] focus:ring-2 focus:ring-[#84976a]/15" placeholder="Add a few details">{{ old('description', $task->description) }}</textarea>
    @error('description') <p class="mt-1.5 text-xs font-medium text-[#bd4e38]">{{ $message }}</p> @enderror
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="due_date" class="mb-1.5 block text-sm font-semibold">Due date <span class="font-normal text-[#929a91]">(optional)</span></label>
        <input id="due_date" type="date" name="due_date" value="{{ old('due_date', $task->due_date?->toDateString()) }}" class="w-full rounded-lg border border-[#dfe4da] bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-[#84976a] focus:ring-2 focus:ring-[#84976a]/15">
        @error('due_date') <p class="mt-1.5 text-xs font-medium text-[#bd4e38]">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="status" class="mb-1.5 block text-sm font-semibold">Status</label>
        <select id="status" name="status" class="w-full rounded-lg border border-[#dfe4da] bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-[#84976a] focus:ring-2 focus:ring-[#84976a]/15">
            <option value="pending" @selected(old('status', $task->status ?? 'pending') === 'pending')>Pending</option>
            <option value="completed" @selected(old('status', $task->status ?? 'pending') === 'completed')>Completed</option>
        </select>
        @error('status') <p class="mt-1.5 text-xs font-medium text-[#bd4e38]">{{ $message }}</p> @enderror
    </div>
</div>

<div class="flex flex-wrap items-center justify-end gap-3 border-t border-[#edf0e9] pt-5">
    <a href="{{ route('tasks.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-semibold text-[#68736b] hover:bg-[#f4f6f0]">Cancel</a>
    <button type="submit" class="rounded-lg bg-[#1d2b24] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#314439] focus:outline-none focus:ring-2 focus:ring-[#80955a] focus:ring-offset-2">{{ $task->exists ? 'Save changes' : 'Add task' }}</button>
</div>