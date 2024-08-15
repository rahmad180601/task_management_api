<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $task = Task::latest()->paginate(5);
        return TaskResource::collection($task);
    }

    public function store(Request $request)
    {   
        Request()->validate([
            'title' => ['string', 'required'],
            'description' => ['string', 'required'],
            'status' => ['required'],
            'proyek_id' => ['required', 'array'],
            'proyek_id.*' => ['exists:proyek,id'],
        ]);

        $task = $request->user()->task()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'due_date' => $request->input('due_date'),
            'submid_date' => $request->input('submid_date'),
        ]);
    
        $task->proyek()->attach($request->proyek_id);

        $task->load('proyek');

        return response()->json([
            'message' => 'Task Created Successfully',
            'data' => $task
        ], 201);
    }

    public function update(Request $request, Task $task)
    {
        Request()->validate([
            'title' => ['string', 'required'],
            'description' => ['string', 'required'],
            'status' => ['required'],
            'proyek_id' => ['required', 'array'],
            'proyek_id.*' => ['exists:proyek,id'],
        ]);

        $task->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'due_date' => $request->input('due_date'),
            'submid_date' => $request->input('submid_date'),
        ]);

        $task->proyek()->sync($request->proyek_id, true);

        return response([
            'message' => 'Task Updated Succsesfully',
            'data'=> new TaskResource($task),
        ]);
    }

    public function delete(Task $task)
    {
        $task->proyek()->detach();
        $task->delete();
        return response('Task, Deleted Succsesfully');
    }
}
