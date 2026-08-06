@extends('layouts.app')
@section('title', 'Budget')
@section('page-title', 'Budget Tracker')

@section('content')

{{-- Limit Banner --}}
@if($budgetLimit !== null)
<div class="mb-5 flex items-center justify-between px-5 py-3.5 rounded-2xl border
    {{ $limitReached ? 'bg-red-50 border-red-200' : 'bg-amber-50 border-amber-200' }}">
    <div class="flex items-center gap-3">
        <span class="text-xl">{{ $limitReached ? '🔒' : '📊' }}</span>
        <div>
            @if($limitReached)
                <p class="text-sm font-bold text-red-700">Budget limit reached ({{ $budgetCount }}/{{ $budgetLimit }})</p>
                <p class="text-xs text-red-500 mt-0.5">Upgrade to Pro for unlimited budget items.</p>
            @else
                <p class="text-sm font-semibold text-amber-700">{{ $budgetCount }} of {{ $budgetLimit }} budget items used (Free Plan)</p>
                <p class="text-xs text-amber-500 mt-0.5">Upgrade to Pro for unlimited items.</p>
            @endif
        </div>
    </div>
    @if($limitReached)
    <a href="#" @click.prevent="upgradeOpen = true" class="text-xs font-bold text-white bg-gradient-to-r from-pink-600 to-purple-600 px-4 py-2 rounded-full shadow hover:shadow-md transition whitespace-nowrap">
        Upgrade to Pro
    </a>
    @endif
</div>
@endif

{{-- Summary Cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-lg mb-3">📋</div>
        <p class="text-xs text-gray-500 mb-1">Estimated Total</p>
        <p class="text-xl font-black text-gray-900">LKR {{ number_format($summary['estimated']) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center text-lg mb-3">💸</div>
        <p class="text-xs text-gray-500 mb-1">Actual Spent</p>
        <p class="text-xl font-black text-pink-600">LKR {{ number_format($summary['actual']) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-shadow">
        <div class="w-10 h-10 {{ $summary['remaining'] >= 0 ? 'bg-green-50' : 'bg-red-50' }} rounded-xl flex items-center justify-center text-lg mb-3">
            {{ $summary['remaining'] >= 0 ? '✅' : '⚠️' }}
        </div>
        <p class="text-xs text-gray-500 mb-1">Remaining</p>
        <p class="text-xl font-black {{ $summary['remaining'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
            LKR {{ number_format(abs($summary['remaining'])) }}
            {{ $summary['remaining'] < 0 ? '(over)' : '' }}
        </p>
    </div>
</div>

{{-- Budget Items Card --}}
<div class="bg-white rounded-2xl border border-gray-100 card-shadow" x-data="{ open: false }">
    <div class="flex items-center justify-between px-6 py-4">
        <h3 class="font-bold text-gray-900">Budget Items</h3>
        @if(!$limitReached)
        <button @click="open = !open"
                class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white text-sm font-semibold rounded-full shadow-md hover:shadow-lg transition">
            <span x-text="open ? '✕ Cancel' : '+ Add Item'"></span>
        </button>
        @else
        <span class="text-xs text-red-400 font-semibold bg-red-50 px-3 py-1.5 rounded-full">
            🔒 Limit reached — Upgrade to add more
        </span>
        @endif
    </div>

    {{-- Add Form --}}
    @if(!$limitReached)
    <div x-show="open" x-cloak class="border-t border-gray-100 px-6 py-5">
        <form method="POST" action="{{ route('budgets.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Item Name <span class="text-red-400">*</span></label>
                <input type="text" name="item_name" placeholder="e.g. Venue Booking" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Category</label>
                <select name="budget_category_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
                    <option value="">Select category...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Estimated Cost (LKR) <span class="text-red-400">*</span></label>
                <input type="number" name="estimated_cost" placeholder="0" required min="0"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Actual Cost (LKR)</label>
                <input type="number" name="actual_cost" placeholder="0" min="0"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 outline-none">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="text" name="notes" placeholder="e.g. Deposit paid, final payment due 2 weeks before"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-200 focus:border-pink-400 outline-none">
            </div>
            <div class="md:col-span-2 flex justify-end gap-3">
                <button type="button" @click="open = false"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-full hover:bg-gray-50 transition">Cancel</button>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-full shadow-md hover:shadow-lg transition">
                    Add Item
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Info banner --}}
    <div class="mx-6 mb-4 flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
        <span class="text-base mt-0.5">💡</span>
        <p class="text-xs text-blue-700 leading-relaxed">
            <span class="font-semibold">Actual cost is optional when adding an item.</span>
            You can leave it blank now and update it later once you've made the payment — click the <span class="font-semibold">✏️ edit</span> icon on any row to fill it in.
        </p>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-t border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Item</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Category</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Estimated</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actual</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Diff</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($budgets as $budget)
                @php $diff = $budget->estimated_cost - $budget->actual_cost; @endphp
                <tr class="hover:bg-gray-50/50 transition" x-data="{ editing: false }">

                    {{-- View row --}}
                    <template x-if="!editing">
                        <td class="px-6 py-4" colspan="1">
                            <p class="font-medium text-gray-800">{{ $budget->item_name }}</p>
                            @if($budget->notes)
                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[200px]">{{ $budget->notes }}</p>
                            @endif
                        </td>
                    </template>
                    <template x-if="editing">
                        <td class="px-6 py-3" colspan="1">
                            <span class="text-xs font-semibold text-pink-600">Editing: {{ $budget->item_name }}</span>
                        </td>
                    </template>

                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $budget->category?->name ?? '—' }}</td>

                    {{-- Estimated — view --}}
                    <td class="px-6 py-4 text-right text-gray-700" x-show="!editing">
                        {{ number_format($budget->estimated_cost) }}
                    </td>

                    {{-- Actual — view --}}
                    <td class="px-6 py-4 text-right" x-show="!editing">
                        @if($budget->actual_cost > 0)
                            <span class="text-gray-700">{{ number_format($budget->actual_cost) }}</span>
                        @else
                            <span class="text-xs text-gray-300 italic">not set</span>
                        @endif
                    </td>

                    {{-- Diff — view --}}
                    <td class="px-6 py-4 text-right" x-show="!editing">
                        @if($budget->actual_cost > 0)
                        <span class="text-xs font-semibold {{ $diff >= 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff) }}
                        </span>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>

                    {{-- Edit form (spans estimated + actual + diff columns) --}}
                    <td class="px-4 py-3 text-right" x-show="editing" colspan="3">
                        <form method="POST" action="{{ route('budgets.update', $budget) }}"
                              class="flex items-center justify-end gap-2 flex-wrap">
                            @csrf @method('PUT')
                            <div class="flex items-center gap-1.5">
                                <label class="text-xs text-gray-500 whitespace-nowrap">Estimated</label>
                                <input type="number" name="estimated_cost"
                                       value="{{ $budget->estimated_cost }}" min="0"
                                       class="w-28 px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-pink-200 outline-none">
                            </div>
                            <div class="flex items-center gap-1.5">
                                <label class="text-xs text-gray-500 whitespace-nowrap">Actual</label>
                                <input type="number" name="actual_cost"
                                       value="{{ $budget->actual_cost > 0 ? $budget->actual_cost : '' }}"
                                       placeholder="0" min="0"
                                       class="w-28 px-3 py-1.5 border border-pink-300 rounded-lg text-xs focus:ring-2 focus:ring-pink-200 outline-none bg-pink-50">
                            </div>
                            <div class="flex items-center gap-1.5 w-full">
                                <label class="text-xs text-gray-500 whitespace-nowrap">Notes</label>
                                <input type="text" name="notes"
                                       value="{{ $budget->notes }}"
                                       placeholder="Optional note..."
                                       class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-pink-200 outline-none">
                            </div>
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-pink-600 to-purple-600 rounded-lg shadow hover:shadow-md transition">
                                Save
                            </button>
                            <button type="button" @click="editing = false"
                                    class="px-3 py-1.5 text-xs font-medium text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                Cancel
                            </button>
                        </form>
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-4 text-right" x-show="!editing">
                        <div class="flex items-center justify-end gap-1">
                            <button @click="editing = true"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-pink-500 hover:bg-pink-50 transition"
                                    title="Edit">
                                ✏️
                            </button>
                            <form method="POST" action="{{ route('budgets.destroy', $budget) }}">
                                @csrf @method('DELETE')
                                <button class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 hover:text-red-400 hover:bg-red-50 transition"
                                        title="Delete"
                                        onclick="return confirm('Delete this budget item?')">
                                    ✕
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <p class="text-3xl mb-2">💰</p>
                        <p class="text-sm font-medium text-gray-500">No budget items yet.</p>
                        <p class="text-xs text-gray-400 mt-1">Click "Add Item" to start tracking your wedding budget.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>

            @if($budgets->count() > 0)
            <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                <tr>
                    <td colspan="2" class="px-6 py-3 text-xs font-bold text-gray-600 uppercase tracking-wide">Total</td>
                    <td class="px-6 py-3 text-right text-sm font-black text-gray-800">{{ number_format($summary['estimated']) }}</td>
                    <td class="px-6 py-3 text-right text-sm font-black text-pink-600">{{ number_format($summary['actual']) }}</td>
                    <td class="px-6 py-3 text-right text-sm font-black {{ $summary['remaining'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $summary['remaining'] >= 0 ? '+' : '' }}{{ number_format($summary['remaining']) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
