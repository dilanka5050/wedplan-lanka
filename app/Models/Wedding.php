<?php

namespace App\Models;

use App\Enums\WeddingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bride_name', 'groom_name', 'wedding_date',
        'wedding_type', 'venue', 'description', 'cover_image',
        'custom_upload_limit', 'allow_admin_access',
        'last_accessed_by', 'last_accessed_at', 'vault_archived_at',
    ];

    protected function casts(): array
    {
        return [
            'wedding_date'       => 'date',
            'wedding_type'       => WeddingType::class,
            'allow_admin_access' => 'boolean',
            'last_accessed_at'   => 'datetime',
            'vault_archived_at'  => 'datetime',
        ];
    }

    public function isVaultArchived(): bool
    {
        return $this->vault_archived_at !== null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lastAccessedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_accessed_by');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(Timeline::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function invitation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Invitation::class);
    }

    public function seatingTables(): HasMany
    {
        return $this->hasMany(SeatingTable::class);
    }

    public function getDaysUntilWeddingAttribute(): ?int
    {
        return $this->wedding_date?->diffInDays(now(), false) * -1;
    }

    public function getProgressAttribute(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) return 0;
        $completed = $this->tasks()->where('status', 'completed')->count();
        return (int) round(($completed / $total) * 100);
    }

    public function getBudgetSummaryAttribute(): array
    {
        return [
            'estimated' => $this->budgets()->sum('estimated_cost'),
            'actual' => $this->budgets()->sum('actual_cost'),
            'remaining' => $this->budgets()->sum('estimated_cost') - $this->budgets()->sum('actual_cost'),
        ];
    }
}
