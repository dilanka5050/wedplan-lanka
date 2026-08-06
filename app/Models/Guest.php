<?php

namespace App\Models;

use App\Enums\RsvpStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id', 'guest_group_id', 'name', 'email', 'phone',
        'invitation_type', 'total_invited', 'rsvp_confirmed',
        'rsvp_status', 'dietary_requirements', 'notes', 'gifted_amount',
        'invite_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (Guest $guest) {
            $guest->invite_token ??= \Illuminate\Support\Str::random(32);
        });
    }

    protected function casts(): array
    {
        return [
            'rsvp_status'    => RsvpStatus::class,
            'total_invited'  => 'integer',
            'rsvp_confirmed'  => 'integer',
            'gifted_amount'   => 'decimal:2',
        ];
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(GuestGroup::class, 'guest_group_id');
    }

    public function seatingAssignment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SeatingAssignment::class);
    }

    // Label for invitation type
    public function getInvitationLabelAttribute(): string
    {
        return match ($this->invitation_type) {
            'couple' => '👫 Couple',
            'family' => '👨‍👩‍👧‍👦 Family',
            default  => '👤 Single',
        };
    }

    // Default total_invited based on type if not manually set
    public static function defaultTotalForType(string $type): int
    {
        return match ($type) {
            'couple' => 2,
            'family' => 4,
            default  => 1,
        };
    }
}
