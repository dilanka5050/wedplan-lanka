<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = ['wedding_id', 'name', 'path', 'type', 'size', 'usage'];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function getUrlAttribute(): ?string
    {
        if (!$this->path) return null;
        try {
            return Storage::temporaryUrl($this->path, now()->addDay());
        } catch (\Exception $e) {
            return Storage::url($this->path);
        }
    }
}
