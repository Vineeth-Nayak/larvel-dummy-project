<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        return response()->json(Task::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'completed' => 'required|boolean',
        ]);
    
        $task->update($validated);
    
        return response()->json(['message' => 'Task status updated successfully'], 200);
    }
    
    public function update(Request $request, Task $task)
    {
        $task->name = $request->name;
        $task->save();
        return response()->json(['message' => 'Task updated successfully'], 200);
    }


}



