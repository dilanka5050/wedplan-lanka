<?php

namespace Database\Seeders;

use App\Models\BudgetCategory;
use App\Models\GuestGroup;
use App\Models\Plan;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Plans
        $freePlan = Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'max_guests' => 30,
            'max_tasks' => 20,
            'max_budgets' => 5,
            'max_uploads' => 5,
            'has_timeline' => false,
            'has_templates' => false,
            'has_collaboration' => false,
            'features' => ['checklist', 'budget', 'guests'],
        ]);

        $proPlan = Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'price' => 2999,
            'max_guests' => 9999,
            'max_tasks' => 9999,
            'max_budgets' => 9999,
            'max_uploads' => 100,
            'has_timeline' => true,
            'has_templates' => true,
            'has_collaboration' => true,
            'features' => ['checklist', 'budget', 'guests', 'timeline', 'templates', 'uploads', 'collaboration', 'separate_guest_lists', 'invitations', 'seating'],
        ]);

        // Task Categories
        $taskCategories = [
            ['name' => 'Venue', 'slug' => 'venue', 'icon' => '🏛️', 'sort_order' => 1],
            ['name' => 'Photography', 'slug' => 'photography', 'icon' => '📸', 'sort_order' => 2],
            ['name' => 'Bridal Dressing', 'slug' => 'bridal-dressing', 'icon' => '👰', 'sort_order' => 3],
            ['name' => 'Decorations', 'slug' => 'decorations', 'icon' => '🌸', 'sort_order' => 4],
            ['name' => 'Poruwa / Ceremony', 'slug' => 'poruwa', 'icon' => '🪔', 'sort_order' => 5],
            ['name' => 'Catering', 'slug' => 'catering', 'icon' => '🍽️', 'sort_order' => 6],
            ['name' => 'Entertainment', 'slug' => 'entertainment', 'icon' => '🎵', 'sort_order' => 7],
        ];
        foreach ($taskCategories as $cat) {
            TaskCategory::create($cat);
        }

        // Budget Categories
        $budgetCategories = [
            ['name' => 'Venue & Reception', 'slug' => 'venue', 'icon' => '🏛️', 'sort_order' => 1],
            ['name' => 'Photography & Video', 'slug' => 'photography', 'icon' => '📸', 'sort_order' => 2],
            ['name' => 'Attire & Dressing', 'slug' => 'attire', 'icon' => '👗', 'sort_order' => 3],
            ['name' => 'Decorations & Flowers', 'slug' => 'decorations', 'icon' => '🌺', 'sort_order' => 4],
            ['name' => 'Catering', 'slug' => 'catering', 'icon' => '🍽️', 'sort_order' => 5],
            ['name' => 'Entertainment', 'slug' => 'entertainment', 'icon' => '🎶', 'sort_order' => 6],
            ['name' => 'Transport', 'slug' => 'transport', 'icon' => '🚗', 'sort_order' => 7],
            ['name' => 'Invitations & Stationery', 'slug' => 'invitations', 'icon' => '💌', 'sort_order' => 8],
            ['name' => 'Other', 'slug' => 'other', 'icon' => '📦', 'sort_order' => 9],
        ];
        foreach ($budgetCategories as $cat) {
            BudgetCategory::create($cat);
        }

        // Guest Groups
        $guestGroups = [
            ['name' => 'Bride Side', 'slug' => 'bride-side'],
            ['name' => 'Groom Side', 'slug' => 'groom-side'],
            ['name' => 'Family', 'slug' => 'family'],
            ['name' => 'Friends', 'slug' => 'friends'],
            ['name' => 'Office', 'slug' => 'office'],
        ];
        foreach ($guestGroups as $group) {
            GuestGroup::create($group);
        }

        // Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@wedplanlanka.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);
        $admin->subscriptions()->create([
            'plan_id' => $proPlan->id,
            'starts_at' => now(),
            'is_active' => true,
        ]);

        // Demo Couple
        $couple = User::create([
            'name' => 'Demo Couple',
            'email' => 'demo@wedplanlanka.com',
            'password' => Hash::make('password'),
            'role' => 'couple',
            'email_verified_at' => now(),
        ]);
        $couple->subscriptions()->create([
            'plan_id' => $freePlan->id,
            'starts_at' => now(),
            'is_active' => true,
        ]);
    }
}
