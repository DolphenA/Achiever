<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Just return the view - tasks will be loaded dynamically via AJAX
        return view('home');
    }

    public function getAllTasks(Request $request)
    {
        // Just return the view - tasks will be loaded dynamically via AJAX
        return view('alltasks');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable',
            'priority' => 'required|in:low,medium,high',
            'file' => 'nullable|file|max:5120000', // 5GB max
        ]);

        $task = new Task($validated);
        
        // Set user_id from session or cookie
        $userId = session('user_id') ?? $request->cookie('user_id');
        if ($userId) {
            $task->user_id = $userId;
        }
        
        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('tasks', $fileName, 'public');
            $task->file_path = $filePath;
        }

        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'task' => $task
        ]);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable',
            'priority' => 'required|in:low,medium,high',
            'file' => 'nullable|file|max:5120000',
        ]);

        $task->fill($validated);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($task->file_path && \Storage::disk('public')->exists($task->file_path)) {
                \Storage::disk('public')->delete($task->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('tasks', $fileName, 'public');
            $task->file_path = $filePath;
        }

        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'task' => $task
        ]);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        // Delete file if exists
        if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
            Storage::disk('public')->delete($task->file_path);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

    public function toggleComplete($id)
    {
        $task = Task::findOrFail($id);
        $task->completed = !$task->completed;
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task status updated',
            'task' => $task
        ]);
    }

    public function getStats(Request $request)
    {
        $userId = session('user_id') ?? $request->cookie('user_id');
        
        if ($userId) {
            $totalTasks = Task::where('user_id', $userId)->count();
            $completedTasks = Task::where('user_id', $userId)->where('completed', true)->count();
        } else {
            $totalTasks = 0;
            $completedTasks = 0;
        }

        return response()->json([
            'success' => true,
            'total' => $totalTasks,
            'completed' => $completedTasks
        ]);
    }

    public function getTasksJson(Request $request)
    {
        $userId = session('user_id') ?? $request->cookie('user_id');
        
        \Log::info('getTasksJson - Session ID: ' . session()->getId());
        \Log::info('getTasksJson - User ID from session: ' . session('user_id'));
        \Log::info('getTasksJson - User ID from cookie: ' . $request->cookie('user_id'));
        
        if ($userId) {
            $tasks = Task::where('user_id', $userId)->orderBy('date', 'asc')->orderBy('time', 'asc')->get();
        } else {
            $tasks = collect();
        }
        
        \Log::info('getTasksJson - Tasks count: ' . $tasks->count());
        
        return response()->json([
            'success' => true,
            'tasks' => $tasks
        ]);
    }
}
