<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
    public function start(Request $request, User $user)
    {
        $admin = $request->user();

        // Only super_admin or planner can impersonate
        if (!$admin->isAdmin()) {
            abort(403);
        }

        // Cannot impersonate another admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.weddings')
                ->with('error', 'Cannot access an admin account.');
        }

        // Customer must have granted access
        if (!$user->wedding?->allow_admin_access) {
            return redirect()->route('admin.weddings')
                ->with('error', 'This couple has not granted admin access.');
        }

        // Store impersonation in session
        session([
            'impersonating_user_id' => $user->id,
            'impersonator_id'       => $admin->id,
        ]);

        // Update audit trail
        $user->wedding->update([
            'last_accessed_by' => $admin->id,
            'last_accessed_at' => now(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', "Now managing {$user->wedding->bride_name} & {$user->wedding->groom_name}'s wedding.");
    }

    public function stop(Request $request)
    {
        session()->forget(['impersonating_user_id', 'impersonator_id']);

        return redirect()->route('admin.weddings');
    }
}
