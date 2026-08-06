<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetCategory extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'sort_order'];

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }
}
