@extends('layouts.app')
@section('title', 'Wedding Vault')
@section('page-title', 'Wedding Vault')

@section('content')
@php $isPro = auth()->user()->isPro(); @endphp

<div x-data="{ tab: 'inspiration', dragging: false, uploadType: 'image' }" class="max-w-5xl mx-auto">

    {{-- ===== ARCHIVED BANNER ===== --}}
    @if($isArchived)
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-5 flex items-center gap-4">
        <span class="text-3xl flex-shrink-0">🗄️</span>
        <div class="flex-1">
            <p class="text-sm font-bold text-red-800">Your vault has been archived</p>
            <p class="text-xs text-red-600 mt-0.5">
                All files have been removed and new uploads are disabled.
                Archived on {{ $wedding->vault_archived_at->format('d M Y') }}.
                Contact your wedding planner if you need access restored.
            </p>
        </div>
        <a href="https://wa.me/94704653343?text=Hi%2C%20I%20need%20my%20vault%20restored%20on%20WedPlan%20Lanka"
           target="_blank"
           class="flex-shrink-0 bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-md transition whitespace-nowrap">
            Contact Planner
        </a>
    </div>
    @endif

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">📁 Wedding Vault</h2>
            <p class="text-sm text-gray-400 mt-0.5">Store your inspiration images, contracts & documents</p>
        </div>

        {{-- Upload quota bar (all users) --}}
        <div class="bg-white rounded-2xl border border-gray-100 px-4 py-3 min-w-[200px] card-shadow">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-semibold text-gray-600">Storage Used</span>
                <span class="text-xs font-bold {{ $usedPct >= 100 ? 'text-red-500' : 'text-gray-700' }}">
                    {{ $uploadCount }} / {{ $uploadLimit }}
                </span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="h-2 rounded-full transition-all duration-500
                    {{ $usedPct >= 100 ? 'bg-red-400' : ($usedPct >= 80 ? 'bg-amber-400' : 'bg-gradient-to-r from-pink-500 to-purple-500') }}"
                    style="width: {{ $usedPct }}%"></div>
            </div>
            @if($usedPct >= 100)
            <p class="text-xs text-red-500 font-semibold mt-1.5">
                Limit reached · <a href="#" class="underline">Contact us for more storage</a>
            </p>
            @elseif($usedPct >= 80)
            <p class="text-xs text-amber-500 font-medium mt-1">{{ $uploadLimit - $uploadCount }} slots remaining · running low</p>
            @else
            <p class="text-xs text-gray-400 mt-1">
                {{ $uploadLimit - $uploadCount }} slots remaining
                @if($isPro && $wedding->custom_upload_limit === null)
                <span class="text-pink-500 font-semibold">(Pro default)</span>
                @elseif($wedding->custom_upload_limit !== null)
                <span class="text-purple-500 font-semibold">(custom limit)</span>
                @endif
            </p>
            @endif
        </div>
    </div>

    {{-- ===== UPLOAD ZONE ===== --}}
    @if(!$limitReached && !$isArchived)
    <div class="mb-6"
         @dragover.prevent="dragging = true"
         @dragleave.prevent="dragging = false"
         @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.uploadForm.submit()">
        <form action="{{ route('vault.store') }}" method="POST" enctype="multipart/form-data"
              x-ref="uploadForm" id="uploadForm">
            @csrf
            <input type="file" name="file" x-ref="fileInput" class="hidden"
                   accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx"
                   @change="$refs.uploadForm.submit()">
            <input type="hidden" name="type" :value="uploadType">
            <input type="hidden" name="label" x-ref="labelInput">

            {{-- Type toggle --}}
            <div class="flex gap-2 mb-3">
                <p class="text-xs font-semibold text-gray-500 self-center mr-1">Upload as:</p>
                <button type="button"
                        @click="uploadType = 'image'"
                        :class="uploadType === 'image' ? 'bg-pink-600 text-white shadow' : 'bg-white text-gray-500 border border-gray-200 hover:border-pink-300'"
                        class="px-4 py-1.5 rounded-full text-xs font-semibold transition">
                    🖼️ Inspiration
                </button>
                <button type="button"
                        @click="uploadType = 'document'"
                        :class="uploadType === 'document' ? 'bg-purple-600 text-white shadow' : 'bg-white text-gray-500 border border-gray-200 hover:border-purple-300'"
                        class="px-4 py-1.5 rounded-full text-xs font-semibold transition">
                    📄 Document
                </button>
            </div>

            <div @click="$refs.fileInput.click()"
                 :class="dragging ? 'border-pink-400 bg-pink-50 scale-[1.01]' : 'border-gray-200 bg-white hover:border-pink-300 hover:bg-pink-50/30'"
                 class="border-2 border-dashed rounded-2xl p-8 text-center cursor-pointer transition-all duration-200">
                <div class="text-4xl mb-3">☁️</div>
                <p class="text-sm font-semibold text-gray-700">
                    Drop files here or <span class="text-pink-600">click to browse</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Images (JPG, PNG, GIF, WebP) · Documents (PDF, DOC, DOCX, XLS) · Max 10MB per file
                </p>
                <p class="text-xs font-semibold mt-2" :class="uploadType === 'image' ? 'text-pink-500' : 'text-purple-500'">
                    Will be added to: <span x-text="uploadType === 'image' ? '🖼️ Inspiration Board' : '📄 Documents'"></span>
                </p>
            </div>
        </form>
    </div>
    @else
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-center gap-4">
        <span class="text-3xl">🔒</span>
        <div class="flex-1">
            @if($isPro)
            <p class="text-sm font-bold text-amber-800">You've used all {{ $uploadLimit }} upload slots</p>
            <p class="text-xs text-amber-600 mt-0.5">Need more storage? Contact your wedding planner or admin to increase your limit.</p>
            @else
            <p class="text-sm font-bold text-amber-800">You've used all {{ $uploadLimit }} free upload slots</p>
            <p class="text-xs text-amber-600 mt-0.5">Upgrade to Pro for 100 file slots — images, contracts, and more.</p>
            @endif
        </div>
        @if($isPro)
        <a href="mailto:hello@wedplanlanka.com" class="flex-shrink-0 bg-amber-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-md hover:bg-amber-700 transition">
            Contact Admin
        </a>
        @else
        <a href="#" class="flex-shrink-0 btn-gradient text-white text-xs font-bold px-4 py-2 rounded-full shadow-md">
            Upgrade to Pro
        </a>
        @endif
    </div>
    @endif

    {{-- ===== TABS ===== --}}
    <div class="flex gap-1 bg-gray-100 rounded-2xl p-1 mb-6 w-fit">
        <button @click="tab = 'inspiration'"
                :class="tab === 'inspiration' ? 'bg-white shadow text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-2 rounded-xl text-sm transition-all duration-200">
            🖼️ Inspiration Board
            @if($images->count())
            <span class="ml-1.5 text-xs bg-pink-100 text-pink-600 font-bold px-1.5 py-0.5 rounded-full">
                {{ $images->count() }}
            </span>
            @endif
        </button>
        <button @click="tab = 'documents'"
                :class="tab === 'documents' ? 'bg-white shadow text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-2 rounded-xl text-sm transition-all duration-200">
            📄 Documents
            @if($documents->count())
            <span class="ml-1.5 text-xs bg-purple-100 text-purple-600 font-bold px-1.5 py-0.5 rounded-full">
                {{ $documents->count() }}
            </span>
            @endif
        </button>
    </div>

    {{-- ===== SHARED FILE CARD MACRO ===== --}}
    {{-- Renders correctly regardless of file extension in either tab --}}
    @php
        function vaultFileCard($file, $downloadRoute, $destroyRoute) {
            $ext       = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
            $isImage   = in_array($ext, ['jpg','jpeg','png','gif','webp']);
            $iconMap   = [
                'pdf'  => ['📕','bg-red-50','text-red-600'],
                'doc'  => ['📘','bg-blue-50','text-blue-600'],
                'docx' => ['📘','bg-blue-50','text-blue-600'],
                'xls'  => ['📗','bg-green-50','text-green-600'],
                'xlsx' => ['📗','bg-green-50','text-green-600'],
            ];
            $icon = $iconMap[$ext] ?? ['📄','bg-gray-50','text-gray-600'];
            return ['ext' => $ext, 'isImage' => $isImage, 'icon' => $icon];
        }
    @endphp

    {{-- ===== INSPIRATION BOARD TAB ===== --}}
    <div x-show="tab === 'inspiration'" x-cloak>
        @if($images->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center card-shadow">
            <p class="text-5xl mb-4">🌸</p>
            <p class="text-base font-semibold text-gray-700">No inspiration items yet</p>
            <p class="text-sm text-gray-400 mt-1">Upload venue photos, decoration ideas, dress inspiration, mood boards & more</p>
        </div>
        @else
        <div class="columns-2 md:columns-3 gap-4 space-y-4">
            @foreach($images as $file)
            @php $meta = vaultFileCard($file, 'vault.download', 'vault.destroy'); @endphp
            <div class="break-inside-avoid group relative rounded-2xl overflow-hidden bg-gray-100 shadow-sm hover:shadow-lg transition-all duration-300"
                 x-data="{ lightbox: false }">

                {{-- Invitation gallery badge --}}
                @if($file->usage === 'invitation_gallery')
                <div class="absolute top-2 left-2 z-10 bg-purple-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                    🎤 Invitation
                </div>
                @endif

                {{-- Preview area --}}
                @if($meta['isImage'])
                    {{-- Actual image: show thumbnail, click opens lightbox --}}
                    <img src="{{ $file->url }}"
                         alt="{{ $file->name }}"
                         @click="lightbox = true"
                         class="w-full object-cover cursor-zoom-in group-hover:scale-105 transition-transform duration-500">

                    {{-- Lightbox --}}
                    <div x-show="lightbox" x-cloak @click.self="lightbox = false"
                         class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4">
                        <div class="relative max-w-4xl w-full">
                            <img src="{{ $file->url }}" alt="{{ $file->name }}"
                                 class="w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl">
                            <button @click="lightbox = false"
                                    class="absolute -top-3 -right-3 w-8 h-8 bg-white rounded-full text-gray-700 font-bold text-sm hover:bg-gray-100 shadow-lg">
                                ✕
                            </button>
                            <p class="text-white/70 text-xs text-center mt-3">{{ $file->name }}</p>
                        </div>
                    </div>
                @else
                    {{-- Non-image in Inspiration Board: show styled placeholder --}}
                    <div class="w-full h-36 {{ $meta['icon'][1] }} flex flex-col items-center justify-center gap-2">
                        <span class="text-4xl">{{ $meta['icon'][0] }}</span>
                        <span class="text-xs font-bold {{ $meta['icon'][2] }} uppercase">{{ $meta['ext'] }}</span>
                    </div>
                @endif

                {{-- Hover overlay with actions --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent
                            opacity-0 group-hover:opacity-100 transition-opacity duration-300
                            flex flex-col justify-end p-3">
                    <p class="text-white text-xs font-semibold truncate">{{ $file->name }}</p>
                    <p class="text-white/60 text-xs">{{ strtoupper($meta['ext']) }} · {{ number_format($file->size / 1024, 0) }} KB</p>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('vault.download', $file) }}"
                           class="flex-1 text-center bg-white/20 backdrop-blur text-white text-xs font-semibold py-1.5 rounded-lg hover:bg-white/30 transition">
                            ⬇ Download
                        </a>
                        <form action="{{ route('vault.destroy', $file) }}" method="POST"
                              onsubmit="return confirm('Remove this file?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500/80 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-600 transition">
                                🗑
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ===== DOCUMENTS TAB ===== --}}
    <div x-show="tab === 'documents'" x-cloak>
        @if($documents->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center card-shadow">
            <p class="text-5xl mb-4">📋</p>
            <p class="text-base font-semibold text-gray-700">No documents yet</p>
            <p class="text-sm text-gray-400 mt-1">Upload vendor contracts, quotations, booking confirmations & invoices</p>
        </div>
        @else
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden card-shadow">
            <div class="divide-y divide-gray-50">
                @foreach($documents as $file)
                @php $meta = vaultFileCard($file, 'vault.download', 'vault.destroy'); @endphp
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50/50 transition"
                     x-data="{ lightbox: false }">

                    {{-- Thumbnail or icon --}}
                    @if($meta['isImage'])
                        {{-- Image uploaded as document: show small thumbnail, click to preview --}}
                        <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 cursor-zoom-in border border-gray-100"
                             @click="lightbox = true">
                            <img src="{{ $file->url }}" alt="{{ $file->name }}"
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        </div>

                        {{-- Lightbox --}}
                        <div x-show="lightbox" x-cloak @click.self="lightbox = false"
                             class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4">
                            <div class="relative max-w-4xl w-full">
                                <img src="{{ $file->url }}" alt="{{ $file->name }}"
                                     class="w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl">
                                <button @click="lightbox = false"
                                        class="absolute -top-3 -right-3 w-8 h-8 bg-white rounded-full text-gray-700 font-bold text-sm hover:bg-gray-100 shadow-lg">
                                    ✕
                                </button>
                                <p class="text-white/70 text-xs text-center mt-3">{{ $file->name }}</p>
                            </div>
                        </div>
                    @else
                        {{-- Non-image: file type icon --}}
                        <div class="w-12 h-12 {{ $meta['icon'][1] }} rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                            {{ $meta['icon'][0] }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $file->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ strtoupper($meta['ext']) }} · {{ number_format($file->size / 1024, 0) }} KB · {{ $file->created_at->format('d M Y') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('vault.download', $file) }}"
                           class="text-xs font-semibold text-purple-600 bg-purple-50 px-3 py-1.5 rounded-lg hover:bg-purple-100 transition">
                            ⬇ Download
                        </a>
                        <form action="{{ route('vault.destroy', $file) }}" method="POST"
                              onsubmit="return confirm('Remove this document?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs font-semibold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
