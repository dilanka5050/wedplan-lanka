<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeddingRequest;
use App\Services\WeddingService;
use Illuminate\Http\Request;

class WeddingController extends Controller
{
    public function __construct(private WeddingService $weddingService) {}

    public function setup(Request $request)
    {
        $wedding = $request->user()->wedding;
        if ($wedding) {
            return redirect()->route('dashboard');
        }
        return view('wedding.setup');
    }

    public function store(WeddingRequest $request)
    {
        $this->weddingService->createWithDefaults(
            $request->validated(),
            $request->user()->id
        );

        return redirect()->route('dashboard')->with('success', 'Wedding workspace created!');
    }

    public function edit(Request $request)
    {
        return view('wedding.edit', ['wedding' => $request->user()->wedding]);
    }

    public function update(WeddingRequest $request)
    {
        $wedding      = $request->user()->wedding;
        $oldType      = $wedding->wedding_type->value;
        $newType      = $request->validated()['wedding_type'];
        $typeChanged  = $oldType !== $newType;

        // Save all wedding details first
        $wedding->update($request->validated());

        // If type changed and user chose a wipe mode, handle checklist
        if ($typeChanged && $request->filled('wipe_mode')) {
            if ($request->input('wipe_mode') === 'all') {
                $this->weddingService->wipeAllAndRegenerate($wedding->fresh());
                $message = 'Wedding type updated. All tasks cleared and new checklist applied.';
            } else {
                $this->weddingService->wipeDefaultsAndRegenerate($wedding->fresh());
                $message = 'Wedding type updated. Default tasks replaced, your custom tasks kept.';
            }
        } else {
            $message = 'Wedding details updated!';
        }

        return redirect()->back()->with('success', $message);
    }
}
