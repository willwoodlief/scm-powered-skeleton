<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserLog;


class AdminController extends Controller {
    public function index()
    {

        return view('admin.admin_index');
    }

    public function logs()
    {
        $user_logs = UserLog::getLastLogs(); //get all of them

        return view('admin.logs.log_index')->with(compact('user_logs'));
    }

}
