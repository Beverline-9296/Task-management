<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-slate-900">

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Task Manager</h1>
                <p class="text-slate-600 dark:text-slate-400">Keep track of your tasks and stay organized</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Tasks Section -->
                <div class="lg:col-span-2">
                    <!-- Add Task Form -->
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Add New Task</h2>
                        <form action="/tasks" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Task Title</label>
                                <input 
                                    type="text" 
                                    id="title" 
                                    name="title" 
                                    placeholder="Enter task title..."
                                    required
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400"
                                >
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    placeholder="Enter task description..."
                                    rows="3"
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400"
                                ></textarea>
                            </div>
                            <button 
                                type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
                            >
                                Add Task
                            </button>
                        </form>
                    </div>

                    <!-- Tasks List -->
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Your Tasks</h2>
                        </div>
                        
                        @if ($tasks->isEmpty())
                            <div class="px-6 py-12 text-center">
                                <p class="text-slate-500 dark:text-slate-400">No tasks yet. Create one to get started!</p>
                            </div>
                        @else
                            <div class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach ($tasks as $task)
                                    <div class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700 transition duration-150">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-slate-900 dark:text-white mb-1">{{ $task->title }}</h3>
                                                @if ($task->description)
                                                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-3">{{ $task->description }}</p>
                                                @endif
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-block px-3 py-1 rounded text-xs font-medium {{ $task->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                                        {{ $task->status === 'completed' ? '✓ Completed' : '⏱ Pending' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @if($task->status !== 'completed')
                                                    <form action="/tasks/{{ $task->id }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button 
                                                            type="submit"
                                                            class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 font-medium text-sm"
                                                        >
                                                            Complete
                                                        </button>
                                                    </form>
                                                @endif
                                                <a
                                                    href="{{ route('tasks.edit', ['task' => $task->id]) }}"
                                                    class="text-sky-600 hover:text-sky-800 dark:text-sky-300 dark:hover:text-sky-200 font-medium text-sm"
                                                >
                                                    Edit
                                                </a>
                                                <form action="/tasks/{{ $task->id }}" method="POST" onsubmit="return confirm('Delete this task?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit"
                                                        class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium text-sm"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar Stats -->
                <div class="space-y-6">
                    <!-- Stats Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Statistics</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700">
                                <span class="text-slate-600 dark:text-slate-400">Total Tasks</span>
                                <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $tasks->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700">
                                <span class="text-slate-600 dark:text-slate-400">Completed</span>
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $tasks->where('status', 'completed')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600 dark:text-slate-400">Pending</span>
                                <span class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $tasks->where('status', 'pending')->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-800 p-6">
                        <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Tips</h3>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                            <li>✓ Click "Complete" to mark tasks done</li>
                            <li>✓ Use clear titles for better tracking</li>
                            <li>✓ Add descriptions for context</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>