<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'price',
        'max_guests', 'max_tasks', 'max_budgets', 'max_uploads',
        'has_timeline', 'has_templates', 'has_collaboration',
        'features',
    ];

    protected function casts(): array
    {
        return [
            'price'               => 'decimal:2',
            'features'            => 'array',
            'has_timeline'        => 'boolean',
            'has_templates'       => 'boolean',
            'has_collaboration'   => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
