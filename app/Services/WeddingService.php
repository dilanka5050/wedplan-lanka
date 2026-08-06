<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\Wedding;

class WeddingService
{
    public function createWithDefaults(array $data, int $userId): Wedding
    {
        $wedding = Wedding::create(array_merge($data, ['user_id' => $userId]));
        $this->applyDefaultChecklist($wedding);
        return $wedding;
    }

    // Wipe ALL tasks (including custom) then regenerate defaults
    public function wipeAllAndRegenerate(Wedding $wedding): void
    {
        $wedding->tasks()->delete();
        $this->applyDefaultChecklist($wedding);
    }

    // Wipe only default/template tasks then regenerate defaults, keep custom tasks
    public function wipeDefaultsAndRegenerate(Wedding $wedding): void
    {
        $defaultTitles = collect($this->getAllDefaultTitles())->flatten()->toArray();
        $wedding->tasks()->whereIn('title', $defaultTitles)->delete();
        $this->applyDefaultChecklist($wedding);
    }

    public function applyDefaultChecklist(Wedding $wedding): void
    {
        $templates = $this->getSriLankanTemplates($wedding->wedding_type->value ?? 'sinhala');

        foreach ($templates as $categorySlug => $tasks) {
            $category = TaskCategory::where('slug', $categorySlug)->first();
            foreach ($tasks as $index => $taskTitle) {
                Task::create([
                    'wedding_id'       => $wedding->id,
                    'task_category_id' => $category?->id,
                    'title'            => $taskTitle,
                    'status'           => 'pending',
                    'priority'         => 'medium',
                    'sort_order'       => $index,
                    'is_template'      => true,
                ]);
            }
        }
    }

    // Returns all default task titles across all types — used to identify template tasks
    private function getAllDefaultTitles(): array
    {
        $all = [];
        foreach (['sinhala', 'tamil', 'muslim', 'christian'] as $type) {
            foreach ($this->getSriLankanTemplates($type) as $tasks) {
                $all = array_merge($all, $tasks);
            }
        }
        return array_unique($all);
    }

    private function getSriLankanTemplates(string $type): array
    {
        $common = [
            'venue'           => ['Book wedding venue', 'Book reception venue', 'Confirm venue capacity'],
            'photography'     => ['Book photographer', 'Book videographer', 'Plan pre-shoot locations'],
            'bridal-dressing' => ['Book bridal dresser', 'Select saree/dress', 'Book groom stylist'],
            'decorations'     => ['Book decorator', 'Select flower arrangements', 'Plan lighting setup'],
            'catering'        => ['Book caterer', 'Plan menu tasting', 'Confirm guest meal preferences'],
            'entertainment'   => ['Book DJ/Band', 'Plan first dance', 'Arrange sound system'],
        ];

        $specific = match ($type) {
            'sinhala'  => ['poruwa' => ['Book Poruwa ceremony setup', 'Arrange Jayamangala Gatha singers', 'Book Nekath time consultation', 'Arrange oil lamp lighting', 'Plan Poruwa decorations']],
            'tamil'    => ['poruwa' => ['Book Thali ceremony setup', 'Arrange Nadaswaram players', 'Book Muhurtham time', 'Plan Mandapam decorations']],
            'muslim'   => ['poruwa' => ['Arrange Nikah ceremony', 'Book Moulavi', 'Plan Walima reception', 'Arrange Mahar']],
            'christian'=> ['poruwa' => ['Book church', 'Meet with priest/pastor', 'Arrange choir', 'Plan church decorations']],
            default    => ['poruwa' => ['Book ceremony setup']],
        };

        return array_merge($common, $specific);
    }
}
