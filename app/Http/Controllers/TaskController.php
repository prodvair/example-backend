<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\http\Resources\TaskResource;

class TaskController extends Controller
{
    const LIMIT = 10;
    const MAX_LIMIT = 100;

    public function __construct()
    {
        $this->middleware('auth.api');
    }

    public function index(Request $request)
    {
        $taskModel = Task::query();
        $limit = self::LIMIT;
        $where = [
            ['user_id', '=', auth()->user()->id]
        ];

        if ($request->has('search') && $request->search != '') {
            $where[1] = ['title', 'LIKE', "%{$request->search}%"];
        }

        if ($request->per_page && (int) $request->per_page <= self::MAX_LIMIT) {
            $limit = (int) $request->per_page;
        }


        return TaskResource::collection(Task::query()->where($where)->paginate($limit));
    }

    public function store(TaskRequest $request)
    {
        $task = Task::create([
            'title'     => $request->title,
            'content'   => $request->content,
            'color'     => $request->color,
            'user_id'   => auth()->user()->id
        ]);

        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        if (!isset($task->title)) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'not found task',
            ]);
        }

        $task->title    = $request->title;
        $task->content  = $request->content;
        $task->color    = $request->color;

        if ($task->save()) {
            return response()->json([
                'status'    => 'success',
                'task'      => $task,
            ]);
        }

        return response()->json([
            'status'    => 'error',
            'message'   => 'something is wrong',
        ], 500);
    }

    public function destroy(Task $task)
    {
        if (!isset($task->title) || $task->user_id !== auth()->user()->id) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'not found task',
            ]);
        }

        if ($task->delete()) {
            return response()->json([
                'status'    => 'success',
                'message'   => 'Task is deleted',
            ]);
        }

        return response()->json([
            'status'    => 'error',
            'message'   => 'something is wrong',
        ], 500);
    }
}
