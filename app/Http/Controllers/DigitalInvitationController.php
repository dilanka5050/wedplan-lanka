<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wedding;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Cache;

class DigitalInvitationController extends Controller
{
    public const TEMPLATES = [
        'digital1' => ['label' => 'Petals & Gold',    'desc' => 'Floating petals with gold shimmer'],
        'digital2' => ['label' => 'Starry Night',     'desc' => 'Twinkling stars with elegant fade-ins'],
        'digital3' => ['label' => 'Lotus Bloom',      'desc' => 'Blooming lotus with soft candlelight'],
        'digital4' => ['label' => 'Crimson Elegance', 'desc' => 'Luxury envelope reveal with red & gold'],
        'digital5' => ['label' => 'Grand Entrance',   'desc' => 'Couple walks through ornate doors into golden light'],
        'digital6' => ['label' => 'Cinematic Film',   'desc' => 'Luxury video hero with cinematic flash reveal'],
        'digital7' => ['label' => 'Midnight Wedding', 'desc' => 'Night sky theme with moonlit silver accents'],
        'digital8' => ['label' => 'සිංහල',             'desc' => 'සිංහල භාෂාවෙන් සිනමාත්මක විවාහ ආරාධනාව'],
    ];

    // Sections available for customization per template
    public const TEMPLATE_SECTIONS = [
        'digital1' => [
            'couple'    => 'The Couple',
            'details'   => 'Event Details',
            'countdown' => 'Countdown',
            'timeline'  => 'Order of Events',
            'venue'     => 'Venue & Map',
            'rsvp'      => 'RSVP',
            'music'     => 'Background Music',
        ],
        'digital2' => [
            'couple'    => 'The Couple',
            'details'   => 'Event Details',
            'countdown' => 'Countdown',
            'timeline'  => 'Order of Events',
            'venue'     => 'Venue & Map',
            'rsvp'      => 'RSVP',
            'music'     => 'Background Music',
        ],
        'digital3' => [
            'couple'    => 'The Couple',
            'details'   => 'Event Details',
            'countdown' => 'Countdown',
            'timeline'  => 'Order of Events',
            'venue'     => 'Venue & Map',
            'rsvp'      => 'RSVP',
            'music'     => 'Background Music',
        ],
        'digital4' => [
            'couple'    => 'The Couple',
            'details'   => 'Event Details',
            'countdown' => 'Countdown',
            'timeline'  => 'Order of Events',
            'venue'     => 'Venue & Map',
            'rsvp'      => 'RSVP',
            'music'     => 'Background Music',
        ],
        'digital5' => [
            'couple'    => 'The Couple',
            'details'   => 'Event Details',
            'countdown' => 'Countdown',
            'timeline'  => 'Order of Events',
            'venue'     => 'Venue & Map',
            'rsvp'      => 'RSVP',
            'music'     => 'Background Music',
        ],
        'digital6' => [
            'invitation' => 'Sacred Union',
            'couple'     => 'The Couple',
            'love_story' => 'Love Story',
            'details'    => 'Event Details',
            'countdown'  => 'Countdown',
            'timeline'   => 'Order of Events',
            'gallery'    => 'Photo Gallery',
            'venue'      => 'Venue & Map',
            'rsvp'       => 'RSVP',
            'closing'    => 'Closing Message',
            'music'      => 'Background Music',
        ],
        'digital7' => [
            'invitation' => 'Sacred Union',
            'couple'     => 'The Couple',
            'love_story' => 'Love Story',
            'details'    => 'Event Details',
            'countdown'  => 'Countdown',
            'timeline'   => 'Order of Events',
            'gallery'    => 'Photo Gallery',
            'venue'      => 'Venue & Map',
            'rsvp'       => 'RSVP',
            'closing'    => 'Closing Message',
            'music'      => 'Background Music',
        ],
        'digital8' => [
            'invitation' => 'පවිත්ර බැඳීම',
            'couple'     => 'යුවළ',
            'love_story' => 'ආදර කතාව',
            'details'    => 'විස්තර',
            'countdown'  => 'ගණන් කිරීම',
            'timeline'   => 'වැඩසටහන',
            'gallery'    => 'ඡායාරූප',
            'venue'      => 'ස්ථානය',
            'rsvp'       => 'RSVP',
            'closing'    => 'අවසාන පණිවිඩය',
            'music'      => 'සංගීතය',
        ],
    ];

    // Hero videos available for selection
    public const HERO_VIDEOS = [
        'video_hero1','video_hero2','video_hero3','video_hero4','video_hero5',
        'video_hero6','video_hero7','video_hero8','video_hero9','video_hero10',
        'video_hero11','video_hero12','video_hero13','video_hero14','video_hero15',
        'video_hero16','video_hero17','video_hero18','video_hero19','video_hero20',
        'video_hero21','video_hero22','video_hero23','video_hero24','video_hero25',
        'video_hero26','video_hero27','video_hero28','video_hero29','video_hero30',
        'video_hero31','video_hero32',
    ];

    // Returns only templates not disabled by admin
    public static function availableTemplates(): array
    {
        $disabled = Cache::get('disabled_digital_templates', []);
        return array_filter(self::TEMPLATES, fn($k) => !in_array($k, $disabled), ARRAY_FILTER_USE_KEY);
    }

    public function index(Request $request)
    {
        $wedding    = $request->user()->wedding;
        $invitation = $wedding->invitation;

        return view('digital-invitations.index', [
            'wedding'               => $wedding,
            'invitation'            => $invitation,
            'templates'             => self::availableTemplates(),
            'customizableTemplates' => self::TEMPLATE_SECTIONS,
        ]);
    }

    public function preview(Request $request, string $template)
    {
        abort_unless(array_key_exists($template, self::availableTemplates()), 404);

        $invitation = $request->user()->wedding->invitation;

        if (!$invitation) {
            return redirect()->route('digital-invitations.index')
                ->with('error', 'Please fill in your invitation details first.');
        }

        return view("digital-invitations.templates.{$template}", [
            'invitation' => $invitation,
            'guestName'  => null,
            'template'   => $template,
            'cfg'        => $invitation->resolvedConfig(),
        ]);
    }

    public function guestCard(Request $request, Guest $guest)
    {
        $invitation = $request->user()->wedding->invitation;

        if (!$invitation) {
            return redirect()->route('digital-invitations.index')
                ->with('error', 'Please fill in your invitation details first.');
        }

        // Use the persisted template from DB, fall back to digital6
        $template = $invitation->digital_template ?? 'digital6';
        abort_unless(array_key_exists($template, self::TEMPLATES), 404); // guestCard uses all templates (already assigned)

        $guestName = match ($guest->invitation_type) {
            'couple' => 'Mr. & Mrs. ' . $guest->name,
            'family' => 'The ' . $guest->name . ' Family',
            default  => $guest->name,
        };

        return view("digital-invitations.templates.{$template}", [
            'invitation' => $invitation,
            'guestName'  => $guestName,
            'guest'      => $guest,
            'template'   => $template,
            'cfg'        => $invitation->resolvedConfig(),
        ]);
    }

    // Customer-facing customize page
    public function customize(Request $request)
    {
        $invitation = $request->user()->wedding->invitation;

        if (!$invitation) {
            return redirect()->route('digital-invitations.index')
                ->with('error', 'Please fill in your invitation details first.');
        }

        $template = $request->input('template', $invitation->digital_template ?? 'digital6');
        abort_unless(array_key_exists($template, self::TEMPLATE_SECTIONS), 404);
        $sections = self::TEMPLATE_SECTIONS[$template];
        $config   = $invitation->resolvedConfig();

        return view('digital-invitations.customize', [
            'invitation'  => $invitation,
            'template'    => $template,
            'sections'    => $sections,
            'heroVideos'  => self::HERO_VIDEOS,
            'config'      => $config,
            'galleryPhotos' => $request->user()->wedding->files()->where('usage', 'invitation_gallery')->get(),
        ]);
    }

    public function saveCustomize(Request $request)
    {
        $invitation = $request->user()->wedding->invitation;
        abort_if(!$invitation, 404);

        $template  = $request->input('_template', $invitation->digital_template ?? 'digital6');
        abort_unless(array_key_exists($template, self::TEMPLATE_SECTIONS), 404);
        $available = array_keys(self::TEMPLATE_SECTIONS[$template]);

        // Build sections — only keys that exist for this template
        $sections = [];
        foreach ($available as $key) {
            $sections[$key] = $request->boolean('sections.' . $key);
        }

        // Validate hero video — normalize empty string to null
        $heroVideo = $request->input('hero_video') ?: null;
        if ($heroVideo && !in_array($heroVideo, self::HERO_VIDEOS)) {
            $heroVideo = null;
        }

        // Love story items
        $loveStoryItems = [];
        foreach ($request->input('love_story_items', []) as $item) {
            $title = trim($item['title'] ?? '');
            if ($title === '') continue;
            $loveStoryItems[] = [
                'icon'  => trim($item['icon']  ?? ''),
                'label' => trim($item['label'] ?? ''),
                'title' => $title,
                'desc'  => trim($item['desc']  ?? ''),
            ];
        }

        // Timeline events
        $timelineEvents = [];
        foreach ($request->input('timeline_events', []) as $ev) {
            $event = trim($ev['event'] ?? '');
            if ($event === '') continue;
            $timelineEvents[] = [
                'time'  => trim($ev['time'] ?? ''),
                'event' => $event,
                'desc'  => trim($ev['desc'] ?? ''),
            ];
        }

        $invitation->update([
            'template_config' => [
                'hero_video'       => $heroVideo,
                'sections'         => $sections,
                'timeline_events'  => $timelineEvents,
                'love_story_items' => $loveStoryItems,
            ],
        ]);

        return back()->with('success', 'Invitation customized successfully.');
    }

    // Admin-facing customize — works on any wedding by ID
    public function adminCustomize(Request $request, Wedding $wedding)
    {
        $invitation = $wedding->invitation;
        abort_if(!$invitation, 404);

        $template = $invitation->digital_template ?? 'digital6';
        $sections = self::TEMPLATE_SECTIONS[$template] ?? [];
        $config   = $invitation->resolvedConfig();

        return view('digital-invitations.customize', [
            'invitation'  => $invitation,
            'template'    => $template,
            'sections'    => $sections,
            'heroVideos'  => self::HERO_VIDEOS,
            'config'      => $config,
            'adminMode'   => true,
            'wedding'     => $wedding,
            'galleryPhotos' => $wedding->files()->where('usage', 'invitation_gallery')->get(),
        ]);
    }

    public function adminSaveCustomize(Request $request, Wedding $wedding)
    {
        $invitation = $wedding->invitation;
        abort_if(!$invitation, 404);

        $template  = $request->input('_template', $invitation->digital_template ?? 'digital6');
        abort_unless(array_key_exists($template, self::TEMPLATE_SECTIONS), 404);
        $available = array_keys(self::TEMPLATE_SECTIONS[$template]);

        $sections = [];
        foreach ($available as $key) {
            $sections[$key] = $request->boolean('sections.' . $key);
        }

        $heroVideo = $request->input('hero_video') ?: null;
        if ($heroVideo && !in_array($heroVideo, self::HERO_VIDEOS)) {
            $heroVideo = null;
        }

        // Love story items
        $loveStoryItems = [];
        foreach ($request->input('love_story_items', []) as $item) {
            $title = trim($item['title'] ?? '');
            if ($title === '') continue;
            $loveStoryItems[] = [
                'icon'  => trim($item['icon']  ?? ''),
                'label' => trim($item['label'] ?? ''),
                'title' => $title,
                'desc'  => trim($item['desc']  ?? ''),
            ];
        }

        // Timeline events
        $timelineEvents = [];
        foreach ($request->input('timeline_events', []) as $ev) {
            $event = trim($ev['event'] ?? '');
            if ($event === '') continue;
            $timelineEvents[] = [
                'time'  => trim($ev['time'] ?? ''),
                'event' => $event,
                'desc'  => trim($ev['desc'] ?? ''),
            ];
        }

        $invitation->update([
            'template_config' => [
                'hero_video'       => $heroVideo,
                'sections'         => $sections,
                'timeline_events'  => $timelineEvents,
                'love_story_items' => $loveStoryItems,
            ],
        ]);

        return back()->with('success', 'Invitation customized successfully.');
    }

    // Persist the selected template to DB (called from index page)
    public function setTemplate(Request $request)
    {
        $template = $request->input('template');
        abort_unless(array_key_exists($template, self::availableTemplates()), 422);

        $invitation = $request->user()->wedding->invitation;
        abort_if(!$invitation, 404);

        $invitation->update(['digital_template' => $template]);

        return response()->json(['ok' => true]);
    }

    public function galleryUpload(Request $request)
    {
        $wedding = $request->user()->wedding;
        abort_if(!$wedding->invitation, 404);

        $existing = $wedding->files()->where('usage', 'invitation_gallery')->count();
        $remaining = 6 - $existing;

        if ($remaining <= 0) {
            return back()->with('gallery_error', 'Maximum 6 gallery photos allowed.');
        }

        $request->validate([
            'gallery_photos'   => 'required|array|max:' . $remaining,
            'gallery_photos.*' => 'image|max:5120|mimes:jpg,jpeg,png,webp',
        ]);

        foreach ($request->file('gallery_photos') as $uploaded) {
            $path = $uploaded->store("weddings/{$wedding->id}/vault", 'public');
            $wedding->files()->create([
                'name'  => $uploaded->getClientOriginalName(),
                'path'  => $path,
                'type'  => 'image',
                'size'  => $uploaded->getSize(),
                'usage' => 'invitation_gallery',
            ]);
        }

        return back()->with('gallery_success', count($request->file('gallery_photos')) . ' photo(s) added.');
    }

    public function galleryDelete(Request $request, File $file)
    {
        $wedding = $request->user()->wedding;
        abort_unless($file->wedding_id === $wedding->id && $file->usage === 'invitation_gallery', 403);

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return back()->with('gallery_success', 'Photo removed.');
    }
}
