<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        return User::all();
    }

    // Show a single user
    public function show($id)
    {
        return User::findOrFail($id);
    }

    // Create a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json($user, 201);
    }

    // Update a user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:admin,user',
        ]);

        $user->fill($request->only(['name', 'email', 'role']));
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json($user);
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    // User dashboard summary
    public function summary(Request $request)
    {
        $user = $request->user();
        $pending = $user->tasks()->where('status', 'pending')->count();
        $inProgress = $user->tasks()->where('status', 'in_progress')->count();
        $completed = $user->tasks()->where('status', 'completed')->count();
        $users = \App\Models\User::count();
        return response()->json([
            'pending' => $pending,
            'in_progress' => $inProgress,
            'completed' => $completed,
            'users' => $users,
        ]);
    }

    // Admin dashboard summary
    public function adminSummary()
    {
        $users = \App\Models\User::count();
        $tasks = \App\Models\Task::count();
        $pending = \App\Models\Task::where('status', 'pending')->count();
        $inProgress = \App\Models\Task::where('status', 'in_progress')->count();
        $completed = \App\Models\Task::where('status', 'completed')->count();
        return response()->json([
            'users' => $users,
            'tasks' => $tasks,
            'pending' => $pending,
            'in_progress' => $inProgress,
            'completed' => $completed,
        ]);
    }
} 