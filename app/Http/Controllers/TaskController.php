<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();

        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        Task::create($request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]) + ['status' => 'pending']);

        return redirect()->route('dashboard');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|in:pending,completed',
        ]));

        return redirect()->route('dashboard');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('dashboard');
    }
}