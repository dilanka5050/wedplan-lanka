<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeatingAssignment extends Model
{
    protected $fillable = ['seating_table_id', 'guest_id'];

    public function table(): BelongsTo
    {
        return $this->belongsTo(SeatingTable::class, 'seating_table_id');
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
