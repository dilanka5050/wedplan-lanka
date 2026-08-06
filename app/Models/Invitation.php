<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = [
        'wedding_id', 'bride_name', 'groom_name',
        'bride_surname', 'groom_surname',
        'wedding_day', 'wedding_location', 'venue_map_url',
        'duration_from', 'duration_to',
        'poruwa_time', 'bride_phone', 'groom_phone', 'template',
        'digital_template', 'template_config',
    ];

    protected $casts = [
        'template_config' => 'array',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    // Resolve template config with defaults — missing keys fall back to true (visible)
    public function resolvedConfig(): array
    {
        $config   = $this->template_config ?? [];
        $sections = $config['sections'] ?? [];

        $defaults = [
            'invitation'  => true,
            'couple'      => true,
            'love_story'  => true,
            'details'     => true,
            'countdown'   => true,
            'timeline'    => true,
            'gallery'     => true,
            'venue'       => true,
            'rsvp'        => true,
            'closing'     => true,
            'music'       => true,
        ];

        $defaultLoveStory = [
            ['icon' => '👋', 'label' => 'The First Meeting', 'title' => 'When Eyes Met',       'desc' => 'A chance encounter that felt written in the stars. Two strangers who somehow felt like home.'],
            ['icon' => '❤️', 'label' => 'The Journey',       'title' => 'Growing Together',    'desc' => 'Through laughter, adventures and quiet moments, our bond grew deeper with every passing day.'],
            ['icon' => '💍', 'label' => 'The Proposal',      'title' => 'Will You?',            'desc' => 'A perfect moment, a question from the heart, and a joyful "Yes" that changed everything forever.'],
            ['icon' => '💏', 'label' => 'The Wedding Day',   'title' => 'Forever Begins',      'desc' => 'The day we say "I do" before our families and begin our forever together.'],
        ];

        $defaultEvents = [
            ['time' => '', 'event' => 'Doors Open',       'desc' => 'Guests welcomed with refreshments & floral arrangements'],
            ['time' => '', 'event' => 'Poruwa Ceremony',  'desc' => 'The sacred auspicious rite uniting two souls'],
            ['time' => '',  'event' => 'Wedding Feast',    'desc' => 'A lavish spread of traditional Sri Lankan cuisine'],
            ['time' => '', 'event' => 'A Fond Farewell',  'desc' => 'Carrying memories that last a lifetime'],
        ];

        return [
            'hero_video'       => $config['hero_video'] ?? null,
            'sections'         => array_merge($defaults, $sections),
            'timeline_events'  => $config['timeline_events'] ?? $defaultEvents,
            'love_story_items' => $config['love_story_items'] ?? $defaultLoveStory,
        ];
    }

    // Parse wedding_day string into parts for the card templates
    // Expects format: "Sunday 11th March 2026"
    public function parsedDay(): array
    {
        $parts = explode(' ', trim($this->wedding_day));
        // parts[0] = Sunday, parts[1] = 11th, parts[2] = March, parts[3] = 2026
        $dayOfWeek  = strtoupper($parts[0] ?? '');
        $dayRaw     = $parts[1] ?? '1st';           // e.g. "11th"
        $month      = strtoupper($parts[2] ?? '');
        $year       = $parts[3] ?? '';

        // Separate number from ordinal suffix: "11th" → number=11, suffix=th
        preg_match('/^(\d+)([a-z]*)$/i', $dayRaw, $m);
        $dayNumber = $m[1] ?? $dayRaw;
        $daySuffix = $m[2] ?? '';

        // Split location into venue name and city
        $locationParts = explode(',', $this->wedding_location, 2);
        $venueName = strtoupper(trim($locationParts[0] ?? $this->wedding_location));
        $venueCity = strtoupper(trim($locationParts[1] ?? ''));

        return compact('dayOfWeek', 'dayNumber', 'daySuffix', 'month', 'year', 'venueName', 'venueCity');
    }
}
