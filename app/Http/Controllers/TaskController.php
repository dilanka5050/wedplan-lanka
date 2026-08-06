<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $wedding = $request->user()->wedding;
        $tasks = $wedding->tasks()->with('category')->orderBy('sort_order')->get();
        $categories = \App\Models\TaskCategory::orderBy('sort_order')->get();

        return view('tasks.index', compact('tasks', 'categories', 'wedding'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'task_category_id' => 'nullable|exists:task_categories,id',
            'priority' => 'in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        $request->user()->wedding->tasks()->create($validated);
        return redirect()->back()->with('success', 'Task added!');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);
        return redirect()->back()->with('success', 'Task updated!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted!');
    }
}
