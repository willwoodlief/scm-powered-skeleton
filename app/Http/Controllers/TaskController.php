<?php

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Helpers\Utilities;
use App\Http\Requests\TodoSaveRequest;
use App\Models\ToDo;
use Illuminate\Http\Request;

class TaskController extends Controller {
    public function index()
    {
        return view('tasks.task_index');
    }

    public function list_tasks()
    {
        return view('tasks.todo_list');
    }

    public function new_task() {
        $task = new ToDo();
        return view('tasks.new_todo',compact('task'));
    }

    public function create_task(TodoSaveRequest $request) {

        $user = Utilities::get_logged_user();
        $task = new ToDo();
        $task->fill($request->validated());
        $task->user_id = $user->id;
        $task->save();
        if ($request->ajax()) {
            return response()->json(['success'=>true,'todo'=>$task]);
        } else {
            return redirect()->back();
        }
    }

    public function delete_task(int $todo_id,Request $request) {
        $user = Utilities::get_logged_user();
        $todo =  ToDo::where("id",$todo_id)->where('user_id',$user->id)->first();
        if (!$todo) {
            throw new UserException(__("Todo not found or not owned by you"));
        }
        $todo->delete();
        if ($request->ajax()) {
            return response()->json(['success'=>true,'message'=>"Deleted todo",'todo'=>$todo]);
        } else {
            return redirect()->back();
        }
    }
}
