<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SeatingTable extends Model
{
    protected $fillable = ['wedding_id', 'name', 'capacity'];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(SeatingAssignment::class);
    }

    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(Guest::class, 'seating_assignments')
                    ->withTimestamps();
    }

    public function getSeatedCountAttribute(): int
    {
        // Sum confirmed participants of all assigned guests
        return $this->guests->sum(fn($g) => max(1, (int) $g->rsvp_confirmed));
    }

    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->capacity - $this->seated_count);
    }
}
