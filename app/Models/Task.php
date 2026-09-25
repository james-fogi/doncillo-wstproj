<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['task_name', 'description', 'status', 'due_date'])]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending'
            && $this->due_date !== null
            && $this->due_date->isBefore(today());
    }
}
