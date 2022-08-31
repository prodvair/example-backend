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


        return TaskResource::collection(Task::query()->where($where)->orderBy('created_at', 'DESC')->paginate($limit));
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
        $task->title    = $request->title;
        $task->content  = $request->content;
        $task->color    = $request->color;

        $task->save();

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->user()->id) {
            return response()->json([
                'message'   => 'not found task',
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message'   => 'Task is deleted',
        ]);
    }
}
