<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DigitalInvitationController;
use App\Models\File;
use App\Models\Invitation;
use App\Models\Plan;
use App\Models\User;
use App\Models\Wedding;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ── DASHBOARD ──────────────────────────────────────────────────────────

    public function dashboard()
    {
        $totalCouples  = User::where('role', 'couple')->count();
        $proUsers      = User::where('role', 'couple')
                            ->whereHas('activeSubscription.plan', fn($q) => $q->where('slug', 'pro'))
                            ->count();
        $freeUsers     = $totalCouples - $proUsers;
        $newThisWeek   = User::where('role', 'couple')->where('created_at', '>=', now()->subWeek())->count();
        $totalWeddings = Wedding::count();
        $upcoming      = Wedding::whereNotNull('wedding_date')->where('wedding_date', '>=', now())->count();
        $totalFiles    = File::count();
        $storageMB     = round(File::sum('size') / 1024 / 1024, 1);

        $recentUsers = User::where('role', 'couple')
            ->with(['activeSubscription.plan', 'wedding'])
            ->latest()->limit(10)->get();

        $storageAlerts = Wedding::with(['user.activeSubscription.plan'])
            ->withCount('files')
            ->having('files_count', '>', 0)
            ->orderByDesc('files_count')
            ->limit(10)->get()
            ->map(function ($w) {
                $limit = $w->custom_upload_limit
                    ?? $w->user->activeSubscription?->plan?->max_uploads
                    ?? 0;
                $pct = $limit > 0 ? round(($w->files_count / $limit) * 100) : 0;
                return array_merge($w->toArray(), [
                    'effective_limit' => $limit,
                    'usage_pct'       => $pct,
                    'wedding_obj'     => $w,
                ]);
            });

        return view('admin.dashboard', compact(
            'totalCouples', 'proUsers', 'freeUsers', 'newThisWeek',
            'totalWeddings', 'upcoming', 'totalFiles', 'storageMB',
            'recentUsers', 'storageAlerts'
        ));
    }

    // ── USERS ──────────────────────────────────────────────────────────────

    public function users(Request $request)
    {
        $q = User::where('role', 'couple')->with(['activeSubscription.plan', 'wedding']);

        if ($search = $request->input('search')) {
            $q->where(fn($s) => $s->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }

        $users = $q->latest()->paginate(20)->withQueryString();

        return view('admin.users', compact('users', 'search'));
    }

    public function editUser(User $user)
    {        return view('admin.user-edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User updated.');
    }

    public function resetPassword(User $user)
    {
        $password = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz'), 0, 4)
                  . substr(str_shuffle('0123456789'), 0, 3)
                  . substr(str_shuffle('@#$!%*?&'), 0, 2);
        $password = str_shuffle($password);

        $user->update(['password' => Hash::make($password)]);

        return back()->with('reset_password', "New password for {$user->name}: {$password}");
    }

    public function upgradePlan(Request $request, User $user)
    {
        $slug = $request->input('plan') === 'pro' ? 'pro' : 'free';
        $plan = Plan::where('slug', $slug)->firstOrFail();

        $user->subscriptions()->where('is_active', true)->update(['is_active' => false]);
        $user->subscriptions()->create(['plan_id' => $plan->id, 'starts_at' => now(), 'is_active' => true]);

        return back()->with('success', "User moved to {$plan->name} plan.");
    }

    // ── WEDDINGS ───────────────────────────────────────────────────────────

    public function weddings(Request $request)
    {
        $q = Wedding::with(['user.activeSubscription.plan', 'lastAccessedBy'])->withCount('files');

        if ($search = $request->input('search')) {
            $q->where(fn($s) => $s
                ->where('bride_name', 'like', "%{$search}%")
                ->orWhere('groom_name', 'like', "%{$search}%")
                ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
            );
        }

        $weddings = $q->latest()->paginate(20)->withQueryString();

        return view('admin.weddings', compact('weddings', 'search'));
    }

    public function editWedding(Wedding $wedding)
    {        $wedding->load(['user', 'files']);
        return view('admin.wedding-edit', compact('wedding'));
    }

    public function updateWedding(Request $request, Wedding $wedding)
    {
        $data = $request->validate([
            'bride_name'          => 'required|string|max:255',
            'groom_name'          => 'required|string|max:255',
            'wedding_date'        => 'nullable|date',
            'venue'               => 'nullable|string|max:255',
            'custom_upload_limit' => 'nullable|integer|min:1|max:9999',
            'allow_admin_access'  => 'boolean',
        ]);

        $data['allow_admin_access'] = $request->boolean('allow_admin_access');
        $data['custom_upload_limit'] = $request->filled('custom_upload_limit')
            ? $data['custom_upload_limit'] : null;

        $wedding->update($data);

        return back()->with('success', 'Wedding updated.');
    }

    public function archiveVault(Wedding $wedding)
    {
        if ($wedding->isVaultArchived()) {
            $wedding->update(['vault_archived_at' => null]);
            return back()->with('success', 'Vault unarchived.');
        }

        $deleted = 0;
        foreach ($wedding->files as $file) {
            Storage::disk(config('filesystems.default'))->delete($file->path);
            $file->delete();
            $deleted++;
        }
        $wedding->update(['vault_archived_at' => now()]);

        return back()->with('success', "{$deleted} file(s) deleted and vault archived.");
    }

    public function setStorageLimit(Request $request, Wedding $wedding)
    {        $request->validate(['custom_upload_limit' => 'required|integer|min:1|max:9999']);
        $wedding->update(['custom_upload_limit' => $request->custom_upload_limit]);
        return back()->with('success', 'Storage limit updated.');
    }

    // ── SETTINGS ───────────────────────────────────────────────────────────

    public function settings()
    {
        $debugMode       = (bool) Cache::get('admin_debug_mode', false);
        $disabled        = Cache::get('disabled_digital_templates', []);
        $templateStats   = $this->buildTemplateStats($disabled);
        $proPlan         = Plan::where('slug', 'pro')->first();

        return view('admin.settings', compact('debugMode', 'templateStats', 'proPlan'));
    }

    public function updatePrice(Request $request)
    {
        $request->validate(['price' => 'required|numeric|min:0']);
        Plan::where('slug', 'pro')->update(['price' => $request->price]);
        return back()->with('success', 'Pro plan price updated.');
    }

    public function toggleDebug()
    {        $new = !(bool) Cache::get('admin_debug_mode', false);
        Cache::forever('admin_debug_mode', $new);
        return back()->with('success', 'Debug mode ' . ($new ? 'enabled' : 'disabled') . '.');
    }

    public function toggleTemplate(Request $request)
    {        $key      = $request->input('key');
        $disabled = Cache::get('disabled_digital_templates', []);
        $stats    = $this->buildTemplateStats($disabled);

        if (!isset($stats[$key])) return back()->with('error', 'Invalid template.');

        if (in_array($key, $disabled)) {
            $disabled = array_values(array_filter($disabled, fn($k) => $k !== $key));
            Cache::forever('disabled_digital_templates', $disabled);
            return back()->with('success', "Template '{$stats[$key]['label']}' enabled.");
        }

        if ($stats[$key]['has_active']) {
            return back()->with('error',
                "Cannot disable '{$stats[$key]['label']}' — {$stats[$key]['count']} customer(s) have an upcoming wedding on " .
                $stats[$key]['latest_date']->format('M j, Y') . '.'
            );
        }

        $disabled[] = $key;
        Cache::forever('disabled_digital_templates', $disabled);
        return back()->with('success', "Template '{$stats[$key]['label']}' disabled.");
    }

    private function buildTemplateStats(array $disabled): array
    {
        $rows = Invitation::query()
            ->join('weddings', 'weddings.id', '=', 'invitations.wedding_id')
            ->whereNotNull('invitations.digital_template')
            ->select(
                'invitations.digital_template as tpl',
                DB::raw('COUNT(*) as cnt'),
                DB::raw('MAX(weddings.wedding_date) as latest_date')
            )
            ->groupBy('invitations.digital_template')
            ->get()->keyBy('tpl');

        $stats = [];
        foreach (DigitalInvitationController::TEMPLATES as $key => $tpl) {
            $row          = $rows->get($key);
            $latestCarbon = $row?->latest_date ? Carbon::parse($row->latest_date) : null;
            $stats[$key]  = [
                'label'       => $tpl['label'],
                'desc'        => $tpl['desc'],
                'count'       => $row ? (int) $row->cnt : 0,
                'latest_date' => $latestCarbon,
                'has_active'  => $latestCarbon && $latestCarbon->isFuture(),
                'enabled'     => !in_array($key, $disabled),
            ];
        }
        return $stats;
    }
}
