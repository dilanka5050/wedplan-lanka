<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id', 'task_category_id', 'title', 'description',
        'status', 'priority', 'due_date', 'is_template', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'status' => TaskStatus::class,
            'priority' => Priority::class,
            'is_template' => 'boolean',
        ];
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }
}
