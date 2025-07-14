<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\TaskAssigned;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    // List tasks: Admin sees all, user sees their own
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role === 'admin') {
            return Task::with(['assignedTo', 'createdBy'])->get();
        } else {
            return Task::with(['assignedTo', 'createdBy'])->where('assigned_to', $user->id)->get();
        }
    }

    // Show a single task (admin: any, user: only their own)
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $task = Task::with(['assignedTo', 'createdBy'])->findOrFail($id);
        if ($user->role !== 'admin' && $task->assigned_to != $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $task;
    }

    // Admin: Create and assign a task
    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'assigned_to' => 'required|exists:users,id',
        ]);
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'assigned_to' => $request->assigned_to,
            'created_by' => $user->id,
        ]);
        $task->load(['assignedTo', 'createdBy']);
        Mail::to($task->assignedTo->email)->send(new TaskAssigned($task));
        return response()->json($task, 201);
    }

    // Admin: Update any task
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $task = Task::findOrFail($id);
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'deadline' => 'sometimes|date',
            'assigned_to' => 'sometimes|exists:users,id',
            'status' => 'sometimes|in:pending,in_progress,completed',
        ]);
        $task->update($request->only(['title', 'description', 'deadline', 'assigned_to', 'status']));
        return response()->json($task);
    }

    // Admin: Delete any task
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $task = Task::findOrFail($id);
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }

    // User: Update status of their own task
    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();
        $task = Task::findOrFail($id);
        if ($task->assigned_to !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);
        $task->status = $request->status;
        $task->save();
        return response()->json($task);
    }
} 