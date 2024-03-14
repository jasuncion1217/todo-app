<?php

namespace App\Http\Controllers;

use App\Models\tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{
    public function index()
    {
        $importantTasks = tasks::where('important', 1)->get();
        $tasks = tasks::where('important', 0)->get();
        return response()->json([
            'tasks' => $tasks,
            'important' => $importantTasks,
        ]);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 400);
        } else {
            $task = new tasks;
            $task->tasks_name = $request->task_name;
            $task->save();

            return response()->json([
                'status' => 200,
                'message' => 'Task Created Successfully'
            ], 200);
        }
    }

    public function important($id)
    {
        $task = tasks::find($id);
        if ($task->important == 0) {
            $task->important = 1;
            $task->save();

            return response()->json([
                'message' => 'Task has been added to importants'
            ]);
        } else {
            $task->important = 0;
            $task->save();

            return response()->json([
                'message' => 'Task has been removed to importants'
            ]);
        }

    }

    public function markComplete($id)
    {
        $task = tasks::find($id);
        if ($task->completed == 0) {
            $task->completed = 1;
            $task->save();
            return response()->json([
                'message' => 'Task has been marked completed'
            ]);
        }
    }

    public function destroy($id)
    {
        $task = tasks::find($id);
        $task->delete();

        return response()->json([
            'message' => 'Task Deleted Successfully'
        ]);
    }
    public function getEditTask($id)
    {
        $task = tasks::find($id);
        return response()->json([
            'taskEditValues' => $task
        ]);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 400);
        } else {
            $task = tasks::find($id);
            $task->tasks_name = $request->task_name;
            $task->save();

            return response()->json([
                'status' => 200,
                'message' => 'Task Updated Successfully'
            ], 200);
        }
    }
}
