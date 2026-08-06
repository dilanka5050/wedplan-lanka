<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    protected $fillable = ['wedding_id', 'name', 'path', 'type', 'size', 'usage'];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }
}
