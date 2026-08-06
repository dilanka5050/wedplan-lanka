<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'avatar'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
        ];
    }

    public function wedding(): HasOne
    {
        return $this->hasOne(Wedding::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('is_active', true)->latestOfMany();
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [UserRole::SUPER_ADMIN, UserRole::PLANNER]);
    }

    public function isPro(): bool
    {
        return $this->activeSubscription?->plan?->slug === 'pro';
    }

    public function canAccessFeature(string $feature): bool
    {
        $plan = $this->activeSubscription?->plan;
        if (!$plan) return false;
        return in_array($feature, $plan->features ?? []);
    }

    public function reachedLimit(string $type): bool
    {
        $plan = $this->activeSubscription?->plan;
        if (!$plan) return true;

        $wedding = $this->wedding;
        if (!$wedding) return false;

        if ($type === 'uploads' && $wedding->custom_upload_limit !== null) {
            return $wedding->files()->count() >= $wedding->custom_upload_limit;
        }

        if ($plan->slug === 'pro') return false;

        return match ($type) {
            'guests'  => $wedding->guests()->count() >= $plan->max_guests,
            'tasks'   => $wedding->tasks()->count() >= $plan->max_tasks,
            'budgets' => $wedding->budgets()->count() >= $plan->max_budgets,
            'uploads' => $wedding->files()->count() >= $plan->max_uploads,
            default   => false,
        };
    }

    public function getLimit(string $type): ?int
    {
        $plan = $this->activeSubscription?->plan;
        if (!$plan) return null;

        if ($type === 'uploads' && $this->wedding?->custom_upload_limit !== null) {
            return $this->wedding->custom_upload_limit;
        }

        return match ($type) {
            'guests'  => $plan->max_guests,
            'tasks'   => $plan->max_tasks,
            'budgets' => $plan->max_budgets,
            'uploads' => $plan->max_uploads,
            default   => null,
        };
    }
}
