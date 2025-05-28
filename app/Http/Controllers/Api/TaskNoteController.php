<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskNoteController extends Controller
{
    /**
     * Get all notes for a task
     */
    public function index($taskId)
    {
        try {
            $task = Auth::user()->tasks()->findOrFail($taskId);
            $notes = $task->notes()->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'notes' => $notes
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }
    }

    /**
     * Add a note to a task
     */
    public function store(Request $request, $taskId)
    {
        try {
            $task = Auth::user()->tasks()->findOrFail($taskId);

            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $note = $task->notes()->create([
                'content' => $request->content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Note added successfully',
                'note' => $note
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
                'message' => 'Failed to add note'
            ], 500);
        }
    }

    /**
     * Update a note
     */
    public function update(Request $request, $taskId, $noteId)
    {
        try {
            $task = Auth::user()->tasks()->findOrFail($taskId);
            $note = $task->notes()->findOrFail($noteId);

            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $note->update([
                'content' => $request->content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Note updated successfully',
                'note' => $note
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
                'message' => 'Failed to update note'
            ], 404);
        }
    }

    /**
     * Delete a note
     */
    public function destroy($taskId, $noteId)
    {
        try {
            $task = Auth::user()->tasks()->findOrFail($taskId);
            $note = $task->notes()->findOrFail($noteId);
            $note->delete();

            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete note'
            ], 404);
        }
    }
}