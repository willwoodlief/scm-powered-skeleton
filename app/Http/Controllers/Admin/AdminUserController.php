<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\UserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\UserLog;
use App\Plugins\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use TorMorten\Eventy\Facades\Eventy;


class AdminUserController extends Controller {

    public function list_users() {

        $query = function() : Builder {
            $query = User::where('id','>',0);
            return Eventy::filter(Plugin::FILTER_QUERY_USERS_IN_ADMIN_LIST, $query);
        };

        $users = $query()->get();
        return view('admin.users.list_users')->with('users',$users);
    }

    public function new_user() {
        $some_user = new User();
        return view('admin.users.new_user')->with('some_user',$some_user);
    }

    /**
     * @param UserUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse|RedirectResponse
     * @throws \Exception
     */
    public function create_user(UserUpdateRequest $request) {

        $some_user = new User();
        $some_user->fill($request->validated());

        if ($some_user->isDirty('email')) {
            $some_user->email_verified_at = null;
        }

        $some_user->permissions = User::USER_PERMISSION_NORMAL;
        $some_user->api_key = '';
        $some_user->name = $some_user->getName();

        $password = $request->request->get('password');
        if(empty($password)) {
            throw new UserException(__("Need to set password"));
        }
        $some_user->password = password_hash($password, PASSWORD_DEFAULT);


        if ($request->hasFile('profile_picture')) {
            $some_user->process_uploaded_image($request->file('profile_picture'));
        } else {
            $some_user->profile = '';
        }

        $some_user->save();
        UserLog::addLog(sprintf("Created user %s ",$some_user->getName()));
        if ($request->ajax()) {
            return response()->json(['success'=>true,'user'=>$some_user]);
        } else {
            return redirect()->back();
        }
    }

    public function edit_user(int $user_id)
    {
        $start_user = User::find($user_id);
        if (!$start_user) {
            throw new UserException(__("User not found"));
        }
        $query = function() use($user_id,$start_user) : Builder {
            $query = User::where("id",$user_id)->orderBy('id','desc');
            return Eventy::filter(Plugin::FILTER_QUERY_USER_FOR_EDIT, $query,$start_user);
        };
        $some_user = $query()->first();

        if (!$some_user) {
            throw new UserException(__("User not found"));
        }

        return view('admin.users.edit_user')->with('some_user',$some_user);
    }

    /**
     * @param int $user_id
     * @param UserUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse|RedirectResponse
     * @throws \Exception
     */
    public function update_user(int $user_id,UserUpdateRequest $request)
    {
        $some_user =  User::where("id",$user_id)->first();
        if (!$some_user) {
            throw new UserException(__("User not found"));
        }

        $some_user->fill($request->validated());

        if ($some_user->isDirty('email')) {
            $some_user->email_verified_at = null;
        }

        $password = $request->request->get('password');
        if ($password) {
            $some_user->password = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($request->hasFile('profile_picture')) {
            $some_user->process_uploaded_image($request->file('profile_picture'));
        }



        $some_user->save();
        UserLog::addLog(sprintf("Updated user %s ",$some_user->getName()));

        if ($request->request->has('user_roles')) {
            $roles = $request->input('user_roles');
            $some_user->save_roles($roles);
        }

        if ($request->ajax()) {
            return response()->json(['success'=>true,'user'=>$some_user]);
        } else {
            return redirect()->route('admin.users');
        }
    }

    public function delete_user(int $user_id,Request $request) {
        $some_user =  User::where("id",$user_id)->first();
        if (!$some_user) {
            throw new UserException(__("User not found"));
        }
        $some_user->delete();
        if ($request->ajax()) {
            return response()->json(['success'=>true,'message'=>"Deleted todo",'user'=>$some_user]);
        } else {
            return redirect()->route('admin.users');
        }
    }
}
