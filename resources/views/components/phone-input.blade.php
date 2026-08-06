@props(['name', 'value' => '', 'required' => false, 'error' => false])
@php
    // Normalize stored value to digits only after stripping +94 or 0 prefix
    $raw = preg_replace('/\D/', '', $value ?? '');
    if (str_starts_with($raw, '94') && strlen($raw) > 9) {
        $raw = substr($raw, 2);
    } elseif (str_starts_with($raw, '0')) {
        $raw = substr($raw, 1);
    }
@endphp
<div x-data="{ digits: '{{ $raw }}' }"
     class="flex rounded-xl overflow-hidden border {{ $error ? 'border-red-300' : 'border-gray-200' }} focus-within:ring-2 focus-within:ring-pink-200 focus-within:border-pink-400 transition">
    <span class="flex items-center px-3 bg-gray-50 border-r {{ $error ? 'border-red-300' : 'border-gray-200' }} text-sm font-semibold text-gray-500 select-none whitespace-nowrap">
        🇱🇰 +94
    </span>
    <input type="tel" x-model="digits"
           placeholder="771234567"
           maxlength="9"
           {{ $required ? 'required' : '' }}
           class="flex-1 px-3 py-2.5 text-sm outline-none bg-white"
           @input="digits = digits.replace(/\D/g, '').slice(0, 9)">
    <input type="hidden" name="{{ $name }}"
           :value="digits.length ? '94' + digits : ''">
</div>
<p class="text-xs text-gray-400 mt-1">Enter 9 digits after +94 — e.g. 771234567</p>
