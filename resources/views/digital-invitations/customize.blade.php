@extends('layouts.app')
@section('title', 'Customize Invitation')
@section('page-title', 'Customize Digital Invitation')

@section('content')

@if(session('success'))
<div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 flex items-center gap-3">
    <span class="text-green-600 text-lg">✓</span>
    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
</div>
@endif

<form method="POST"
      action="{{ isset($adminMode) ? route('admin.digital-invitations.customize.save', $wedding) : route('digital-invitations.customize.save') }}"
      x-data="customizeApp()" x-init="init()">
    @csrf
    <input type="hidden" name="_template" value="{{ $template }}">

    <div class="grid lg:grid-cols-2 gap-6">

        {{-- LEFT: Controls --}}
        <div class="space-y-6">

            {{-- Section Toggles --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-4">📋 Sections</h3>
                <div class="space-y-3">
                    @foreach($sections as $key => $label)
                    <label class="flex items-center justify-between gap-3 cursor-pointer group">
                        <span class="text-sm text-gray-700 group-hover:text-purple-700 transition">{{ $label }}</span>
                        <div class="relative">
                            <input type="hidden" name="sections[{{ $key }}]" value="0">
                            <input type="checkbox"
                                   name="sections[{{ $key }}]"
                                   value="1"
                                   class="sr-only peer"
                                   {{ ($config['sections'][$key] ?? true) ? 'checked' : '' }}
                                   @change="markDirty(); {{ $key === 'gallery' ? 'galleryEnabled = $event.target.checked' : ($key === 'timeline' ? 'timelineEnabled = $event.target.checked' : ($key === 'love_story' ? 'loveStoryEnabled = $event.target.checked' : '')) }}"
                                   id="toggle-{{ $key }}">
                            <div class="w-10 h-6 bg-gray-200 peer-checked:bg-purple-600 rounded-full transition peer-focus:ring-2 peer-focus:ring-purple-300"></div>
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition peer-checked:translate-x-4"></div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Love Story Items --}}
            @if(isset($sections['love_story']))
            <div x-show="loveStoryEnabled" x-transition class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-1">💕 Love Story</h3>
                <p class="text-xs text-gray-400 mb-4">Edit the milestones shown in the Love Story timeline.</p>
                <div id="love-story-items" class="space-y-3 mb-3">
                    @foreach($config['love_story_items'] as $i => $item)
                    <div class="love-story-row flex gap-2 items-start">
                        <div class="flex flex-col gap-1 flex-1">
                            <input type="text" name="love_story_items[{{ $i }}][icon]" value="{{ $item['icon'] }}"
                                   placeholder="Icon emoji (e.g. 👋)"
                                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
                            <input type="text" name="love_story_items[{{ $i }}][label]" value="{{ $item['label'] }}"
                                   placeholder="Label (e.g. The First Meeting)"
                                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
                            <input type="text" name="love_story_items[{{ $i }}][title]" value="{{ $item['title'] }}"
                                   placeholder="Title *"
                                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
                            <input type="text" name="love_story_items[{{ $i }}][desc]" value="{{ $item['desc'] }}"
                                   placeholder="Short description (optional)"
                                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
                        </div>
                        <button type="button" onclick="removeLoveStoryItem(this)"
                                class="mt-1 text-red-400 hover:text-red-600 text-lg leading-none flex-shrink-0">×</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addLoveStoryItem()"
                        class="w-full text-xs text-purple-600 border border-dashed border-purple-300 rounded-xl py-2 hover:bg-purple-50 transition">+ Add Milestone</button>
            </div>
            @endif

            {{-- Timeline Events --}}
            @if(isset($sections['timeline']))
            <div x-show="timelineEnabled" x-transition class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-1">🕐 Order of Events</h3>
                <p class="text-xs text-gray-400 mb-4">Add, edit or remove events shown in the timeline.</p>
                <div id="timeline-events" class="space-y-3 mb-3">
                    @foreach($config['timeline_events'] as $i => $ev)
                    <div class="timeline-event-row flex gap-2 items-start">
                        <div class="flex flex-col gap-1 flex-1">
                            <input type="text" name="timeline_events[{{ $i }}][time]" value="{{ $ev['time'] }}"
                                   placeholder="Time (e.g. 9:00 AM)"
                                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
                            <input type="text" name="timeline_events[{{ $i }}][event]" value="{{ $ev['event'] }}"
                                   placeholder="Event name *"
                                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
                            <input type="text" name="timeline_events[{{ $i }}][desc]" value="{{ $ev['desc'] }}"
                                   placeholder="Short description (optional)"
                                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
                        </div>
                        <button type="button" onclick="removeTimelineEvent(this)"
                                class="mt-1 text-red-400 hover:text-red-600 text-lg leading-none flex-shrink-0">×</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addTimelineEvent()"
                        class="w-full text-xs text-purple-600 border border-dashed border-purple-300 rounded-xl py-2 hover:bg-purple-50 transition">+ Add Event</button>
            </div>
            @endif

            {{-- Gallery Photos --}}
            @if(isset($sections['gallery']))
            <div x-show="galleryEnabled" x-transition class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-bold text-gray-800">🖼️ Gallery Photos</h3>
                    <span class="text-xs text-gray-400">{{ $galleryPhotos->count() }} / 6</span>
                </div>
                <p class="text-xs text-gray-400 mb-4">These photos appear in the Photo Gallery section of the invitation.</p>

                @if(session('gallery_error'))
                <p class="text-xs text-red-500 mb-3">{{ session('gallery_error') }}</p>
                @endif
                @if(session('gallery_success'))
                <p class="text-xs text-green-600 mb-3">{{ session('gallery_success') }}</p>
                @endif

                @if($galleryPhotos->count())
                <div class="grid grid-cols-3 gap-2 mb-4">
                    @foreach($galleryPhotos as $photo)
                    <div class="relative group rounded-lg overflow-hidden aspect-square bg-gray-100">
                        <img src="{{ Storage::url($photo->path) }}" alt="" class="w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition">
                            <button type="button" onclick="deletePhoto({{ $photo->id }}, this)"
                                    class="text-white text-xs bg-red-500 px-2 py-1 rounded-lg">🗑 Remove</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                @php $remaining = 6 - $galleryPhotos->count(); @endphp
                @if($remaining > 0)
                <label class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-xl p-4 cursor-pointer hover:border-purple-300 hover:bg-purple-50/30 transition" id="gallery-upload-label">
                    <span class="text-2xl mb-1" id="gallery-upload-icon">📷</span>
                    <span class="text-xs font-semibold text-gray-600" id="gallery-upload-text">Click to upload up to {{ $remaining }} photo{{ $remaining > 1 ? 's' : '' }}</span>
                    <span class="text-xs text-gray-400 mt-0.5">JPG, PNG, WebP · Max 5MB each</span>
                    <input type="file" name="gallery_photos[]" accept="image/jpeg,image/png,image/webp" class="hidden"
                           multiple onchange="submitGalleryUpload(this, {{ $remaining }})">
                </label>
                @else
                <p class="text-xs text-center text-amber-600 bg-amber-50 rounded-xl py-2">Maximum 6 photos reached</p>
                @endif
            </div>
            @endif

            {{-- Hero Video Picker (only for templates that support it) --}}
            @php $supportsHeroVideo = in_array($template, ['digital6', 'digital7']); @endphp
            @if($supportsHeroVideo)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-1">🎬 Hero Video</h3>
                <p class="text-xs text-gray-400 mb-4">Click a video to preview. Selected video plays at the start of the invitation.</p>

                <input type="hidden" name="hero_video" id="hero-video-input" value="{{ $config['hero_video'] ?? '' }}">

                <div class="grid grid-cols-3 gap-2 max-h-80 overflow-y-auto pr-1">
                    @foreach($heroVideos as $vid)
                    <div class="relative cursor-pointer rounded-lg overflow-hidden border-2 transition
                                {{ ($config['hero_video'] ?? '') === $vid ? 'border-purple-500' : 'border-transparent hover:border-purple-300' }}"
                         onclick="selectVideo('{{ $vid }}', this)">
                        <video class="w-full aspect-video object-cover bg-gray-900"
                               muted preload="none"
                               poster="{{ asset('back1.png') }}"
                               onmouseenter="this.play()"
                               onmouseleave="this.pause(); this.currentTime=0;">
                            <source src="{{ asset('hero_video/' . $vid . '.mp4') }}" type="video/mp4">
                        </video>
                        <div class="absolute inset-0 flex items-end p-1.5 bg-gradient-to-t from-black/60 to-transparent">
                            <span class="text-white text-xs font-medium">{{ str_replace('video_hero', '#', $vid) }}</span>
                        </div>
                        <div class="selected-check absolute top-1 right-1 bg-purple-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs
                                    {{ ($config['hero_video'] ?? '') === $vid ? '' : 'hidden' }}">✓</div>
                    </div>
                    @endforeach
                </div>

                {{-- Video preview modal --}}
                <div id="video-preview-modal"
                     class="hidden fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
                     onclick="closePreview()">
                    <div class="relative w-full max-w-2xl" onclick="event.stopPropagation()">
                        <video id="preview-player" class="w-full rounded-xl" controls controlsList="nodownload" autoplay muted>
                            <source id="preview-source" src="" type="video/mp4">
                        </video>
                        <button type="button" onclick="closePreview()"
                                class="absolute -top-3 -right-3 bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold shadow">✕</button>
                    </div>
                </div>
            </div>
            @endif

        </div>
        <div class="hidden lg:block">
            <div class="sticky top-20">
                <p class="text-xs text-gray-400 mb-2 text-center">Live Preview</p>
                <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-900" style="height:680px;overflow-y:scroll;">
                    <div id="preview-scaler" style="width:900px;transform:scale(0.38);transform-origin:top left;height:258px;">
                        <iframe id="preview-frame"
                                src="{{ route('digital-invitations.preview', $template) }}?preview=1"
                                style="width:900px;height:680px;border:none;display:block;"
                                scrolling="no"
                                onload="fitPreview(this)"></iframe>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2 text-center">Save to see section changes reflected</p>
            </div>
        </div>

    </div>

    {{-- Save bar --}}
    <div class="mt-6 flex items-center justify-between gap-4 bg-white border border-gray-100 rounded-2xl shadow-sm px-5 py-4">
        <a href="{{ route('digital-invitations.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Back to Templates</a>
        <button type="submit"
                class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-bold rounded-full shadow hover:opacity-90 transition">
            💾 Save Customization
        </button>
    </div>

</form>

<script>
function fitPreview(iframe) {
    try {
        const scale  = 0.38;
        const body   = iframe.contentDocument.body;
        const height = body.scrollHeight;
        iframe.style.height = height + 'px';
        document.getElementById('preview-scaler').style.height = (height * scale) + 'px';
    } catch(e) {}
}

// Re-fit after envelope is opened
window.addEventListener('message', function(e) {
    if (e.data === 'envelope-opened') fitPreview(document.getElementById('preview-frame'));
});

function selectVideo(vid, el) {
    // Update hidden input
    document.getElementById('hero-video-input').value = vid;

    // Update border + check on all cards
    document.querySelectorAll('[onclick^="selectVideo"]').forEach(card => {
        card.classList.remove('border-purple-500');
        card.classList.add('border-transparent');
        card.querySelector('.selected-check')?.classList.add('hidden');
    });
    el.classList.add('border-purple-500');
    el.classList.remove('border-transparent');
    el.querySelector('.selected-check')?.classList.remove('hidden');

    // Open full preview modal
    const modal  = document.getElementById('video-preview-modal');
    const player = document.getElementById('preview-player');
    const source = document.getElementById('preview-source');
    source.src = '{{ asset('hero_video/') }}/' + vid + '.mp4';
    player.load();
    player.play().catch(() => {});
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closePreview() {
    const modal  = document.getElementById('video-preview-modal');
    const player = document.getElementById('preview-player');
    player.pause();
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function customizeApp() {
    return {
        dirty: false,
        galleryEnabled:    {{ ($config['sections']['gallery']    ?? true) ? 'true' : 'false' }},
        timelineEnabled:   {{ ($config['sections']['timeline']   ?? true) ? 'true' : 'false' }},
        loveStoryEnabled:  {{ ($config['sections']['love_story'] ?? true) ? 'true' : 'false' }},
        init() {},
        markDirty() { this.dirty = true; }
    };
}

function submitGalleryUpload(input, remaining) {
    const files = Array.from(input.files);
    if (!files.length) return;
    if (files.length > remaining) {
        alert('You can only add ' + remaining + ' more photo' + (remaining > 1 ? 's' : '') + '.');
        input.value = '';
        return;
    }
    const icon = document.getElementById('gallery-upload-icon');
    const text = document.getElementById('gallery-upload-text');
    icon.textContent = '⏳';
    text.textContent = 'Uploading ' + files.length + ' photo' + (files.length > 1 ? 's' : '') + '...';

    const fd = new FormData();
    fd.append('_token', '{{ csrf_token() }}');
    files.forEach(f => fd.append('gallery_photos[]', f));

    fetch('{{ route('digital-invitations.gallery.upload') }}', { method: 'POST', body: fd })
        .then(r => r.ok ? location.reload() : r.text().then(t => { console.error(t); alert('Upload failed. Check file size/type.'); icon.textContent = '📷'; text.textContent = 'Click to upload'; }))
        .catch(() => { alert('Upload failed.'); icon.textContent = '📷'; text.textContent = 'Click to upload'; });
}

function deletePhoto(id, btn) {
    if (!confirm('Remove this photo?')) return;
    btn.disabled = true;
    btn.textContent = '...';
    const fd = new FormData();
    fd.append('_token', '{{ csrf_token() }}');
    fd.append('_method', 'DELETE');
    fetch('/digital-invitations/gallery/' + id, { method: 'POST', body: fd })
        .then(r => r.ok ? location.reload() : alert('Delete failed.'))
        .catch(() => alert('Delete failed.'));
}

function addLoveStoryItem() {
    const container = document.getElementById('love-story-items');
    const idx = container.querySelectorAll('.love-story-row').length;
    const row = document.createElement('div');
    row.className = 'love-story-row flex gap-2 items-start';
    row.innerHTML = `
        <div class="flex flex-col gap-1 flex-1">
            <input type="text" name="love_story_items[${idx}][icon]" placeholder="Icon emoji (e.g. 👋)"
                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
            <input type="text" name="love_story_items[${idx}][label]" placeholder="Label (e.g. The First Meeting)"
                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
            <input type="text" name="love_story_items[${idx}][title]" placeholder="Title *"
                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
            <input type="text" name="love_story_items[${idx}][desc]" placeholder="Short description (optional)"
                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
        </div>
        <button type="button" onclick="removeLoveStoryItem(this)" class="mt-1 text-red-400 hover:text-red-600 text-lg leading-none flex-shrink-0">×</button>`;
    container.appendChild(row);
    reindexLoveStory();
}

function removeLoveStoryItem(btn) {
    btn.closest('.love-story-row').remove();
    reindexLoveStory();
}

function reindexLoveStory() {
    document.querySelectorAll('#love-story-items .love-story-row').forEach((row, i) => {
        row.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace(/love_story_items\[\d+\]/, `love_story_items[${i}]`);
        });
    });
}

function addTimelineEvent() {
    const container = document.getElementById('timeline-events');
    const idx = container.querySelectorAll('.timeline-event-row').length;
    const row = document.createElement('div');
    row.className = 'timeline-event-row flex gap-2 items-start';
    row.innerHTML = `
        <div class="flex flex-col gap-1 flex-1">
            <input type="text" name="timeline_events[${idx}][time]" placeholder="Time (e.g. 9:00 AM)"
                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
            <input type="text" name="timeline_events[${idx}][event]" placeholder="Event name *"
                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
            <input type="text" name="timeline_events[${idx}][desc]" placeholder="Short description (optional)"
                   class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-purple-400">
        </div>
        <button type="button" onclick="removeTimelineEvent(this)" class="mt-1 text-red-400 hover:text-red-600 text-lg leading-none flex-shrink-0">×</button>`;
    container.appendChild(row);
    reindexTimeline();
}

function removeTimelineEvent(btn) {
    btn.closest('.timeline-event-row').remove();
    reindexTimeline();
}

function reindexTimeline() {
    document.querySelectorAll('#timeline-events .timeline-event-row').forEach((row, i) => {
        row.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace(/timeline_events\[\d+\]/, `timeline_events[${i}]`);
        });
    });
}

// Warn on unsaved changes
window.addEventListener('beforeunload', function(e) {
    if (document.querySelector('form').__x?.$data?.dirty) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>

@endsection
