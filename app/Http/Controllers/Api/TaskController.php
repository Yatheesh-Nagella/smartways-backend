<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Get all tasks for authenticated user
     */
    public function index(Request $request)
    {
        try {
            $filter = $request->get('filter', 'all'); // all, pending, completed
            $priority = $request->get('priority'); // low, medium, high

            $query = Auth::user()->tasks()->with('notes')->orderBy('created_at', 'desc');

            // Apply filters
            if ($filter === 'pending') {
                $query->pending();
            } elseif ($filter === 'completed') {
                $query->completed();
            }

            if ($priority) {
                $query->priority($priority);
            }

            $tasks = $query->get();

            return response()->json([
                'success' => true,
                'tasks' => $tasks,
                'stats' => [
                    'total' => Auth::user()->tasks()->count(),
                    'pending' => Auth::user()->tasks()->pending()->count(),
                    'completed' => Auth::user()->tasks()->completed()->count(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new task
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'priority' => 'required|in:low,medium,high',
            ]);

            $task = Auth::user()->tasks()->create([
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
            ]);

            $task->load('notes');

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific task
     */
    public function show($id)
    {
        try {
            $task = Auth::user()->tasks()->with('notes')->findOrFail($id);

            return response()->json([
                'success' => true,
                'task' => $task
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }
    }

    /**
     * Update a task
     */
    public function update(Request $request, $id)
    {
        try {
            $task = Auth::user()->tasks()->findOrFail($id);

            $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'priority' => 'sometimes|required|in:low,medium,high',
                'completed' => 'sometimes|boolean',
            ]);

            $task->update($request->only(['title', 'description', 'priority', 'completed']));
            $task->load('notes');

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => $task
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task'
            ], 404);
        }
    }

    /**
     * Delete a task
     */
    public function destroy($id)
    {
        try {
            $task = Auth::user()->tasks()->findOrFail($id);
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task'
            ], 404);
        }
    }

    /**
     * Toggle task completion status
     */
    public function toggle($id)
    {
        try {
            $task = Auth::user()->tasks()->findOrFail($id);
            $task->completed = !$task->completed;
            $task->save();
            $task->load('notes');

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully',
                'task' => $task
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task status'
            ], 404);
        }
    }
}