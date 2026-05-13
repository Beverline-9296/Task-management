<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->latest()->get();

        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        Task::create($request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]) + [
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard');
    }

    public function edit(Task $task)
    {
        // Ensure the task belongs to the authenticated user
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        // Ensure the task belongs to the authenticated user
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $task->update($request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|in:pending,completed',
        ]));

        return redirect()->route('dashboard');
    }

    public function destroy(Task $task)
    {
        // Ensure the task belongs to the authenticated user
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $task->delete();

        return redirect()->route('dashboard');
    }
}
