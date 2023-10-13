<?php

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Helpers\Utilities;
use App\Http\Requests\NotificationSaveRequest;
use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller {
    public function index()
    {
        $employees = Employee::getAllEmployees();
        $notifications = Notification::getAllNotifications();

        return view('notifications.notification_index')->with(compact('notifications','employees'));
    }

    public function new_notification() {
        $notification = new Notification();
        $employees = Employee::getAllEmployees();
        return view('notifications.notification_new',compact('notification','employees'));
    }

    /**
     * @param NotificationSaveRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create_notification(NotificationSaveRequest $request) {

        $employee_ids = Utilities::to_int_array($request->request->all(NotificationSaveRequest::FORM_NAME_EMPLOYEE_ID_LIST));
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $notification_type = $request->request->get(NotificationSaveRequest::FORM_NAME_NOTIFICATION_TYPE);
        $noti = Notification::createNotification($employee_ids,$title,$description,$notification_type);

        if ($request->ajax()) {
            return response()->json(['success'=>true,'message'=>"Notification Created",'notification'=>$noti]);
        } else {
            return redirect()->back();
        }
    }

    public function delete_notification(int $notification_id,Request $request) {
        $notification =  Notification::where("id",$notification_id)->first();
        if (!$notification) {
            throw new UserException(__("Notification not found: $notification_id"));
        }

        $notification->delete();
        if ($request->ajax()) {
            return response()->json(['success'=>true,'message'=>"Deleted notification",'todo'=>$notification]);
        } else {
            return redirect()->back();
        }
    }

}
