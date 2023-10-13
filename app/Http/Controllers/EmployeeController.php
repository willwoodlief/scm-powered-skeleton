<?php

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Helpers\Employee\EmployeeToUserSync;
use App\Http\Requests\EmployeeSaveRequest;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserLog;
use App\Plugins\Plugin;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use TorMorten\Eventy\Facades\Eventy;

class EmployeeController extends Controller {

    public function index()
    {
        $employees = Employee::getAllEmployees();
        return view('employee.employee_index')
            ->with('employees',$employees);
    }

    public function new_employee()
    {
        $employee = new Employee();
        return view('employee.new_employee',compact('employee'));
    }

    /**
     * @param EmployeeSaveRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function create_employee(EmployeeSaveRequest $request)
    {
        $employee = new Employee();
        $employee->fill($request->validated());

        $employee->status = Employee::DEFAULT_STATUS;
        $employee->password = Employee::DEFAULT_PW;

        if ($request->hasFile('profile_picture')) {
            $employee->process_uploaded_image($request->file('profile_picture'));
        } else {
            $employee->profile = '';
        }
        $employee->save();
        UserLog::addLog(sprintf("Added employee %s ",$employee->getName()));
        return redirect()->intended(RouteServiceProvider::EMPLOYEES);
    }

    public function update_employee_form(int $employee_id)
    {

        $start_employee = Employee::find($employee_id);
        if (!$start_employee) {
            throw new UserException(__("Employee not found in update"));
        }

        $query = function() use($employee_id,$start_employee) : Builder {
            $query = Employee::where("id",$employee_id);
            return Eventy::filter(Plugin::FILTER_QUERY_EMPLOYEE_FOR_EDIT, $query,$start_employee);
        };

        $employee = $query()->first();

        if (!$employee) {
            throw new UserException(__("Employee not found"));
        }
        return view('employee.employee_profile',compact('employee'));
    }

    public function employee_profile(int $employee_id)
    {
        $start_employee = Employee::find($employee_id);
        if (!$start_employee) {
            throw new UserException(__("Employee not found"));
        }

        $query = function() use($employee_id,$start_employee) : Builder {
            $query = Employee::where("id",$employee_id);
            return Eventy::filter(Plugin::FILTER_QUERY_EMPLOYEE_FOR_VIEW, $query,$start_employee);
        };

        $employee = $query()->first();

        if (!$employee) {
            throw new UserException(__("Employee not found"));
        }
        return view('employee.employee_profile',compact('employee'));
    }



    /**
     * @param int $employee_id
     * @param EmployeeSaveRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update_employee(int $employee_id,EmployeeSaveRequest $request)
    {
        $employee =  Employee::where("id",$employee_id)->first();
        if (!$employee) {
            throw new UserException(__("Employee not found"));
        }
        $employee->fill($request->validated());

        if ($request->hasFile('profile_picture')) {
            $employee->process_uploaded_image($request->file('profile_picture'));
        }

        $employee->save();

        UserLog::addLog(sprintf("Updated employee %s ",$employee->getName()));
        return redirect()->route('employee.profile', ['employee_id'=>$employee_id]);
    }

    public function delete_employee(int $employee_id) {
        $employee =  Employee::where("id",$employee_id)->first();
        if (!$employee) {
            throw new UserException(__("Employee not found"));
        }
        $employee->delete();
        return redirect()->intended(RouteServiceProvider::EMPLOYEES);
    }

    public function web_access(int $employee_id,Request $request) {
        $employee =  Employee::where("id",$employee_id)->first();
        if (!$employee) {
            throw new UserException(__("Employee not found"));
        }
        if ($request->request->has('employee_has_web_access')) {
            $b_web_access = (bool)$request->request->getInt('employee_has_web_access');
        } else {
            $b_web_access = false;
        }

        $password = $request->request->getString('employee_password');
        $password_confirm = $request->request->getString('employee_password_confirm');
        if ($password !== $password_confirm) {
            throw new UserException("Password does not match when it is repeated");
        }

        if ($b_web_access) {
            EmployeeToUserSync::syncEmployeeToUser($employee,$password);

            /**
             * @var User $associated_user
             */
            $associated_user = Employee::find($employee_id)->employee_user()->first();
            if (!$associated_user) {
                throw new \LogicException("Could not get the associated user of the employee in web_access");
            }
            $associated_user->can_log_in = User::USER_STATUS_NORMAL;
            $associated_user->save();
        } else {
            /**
             * @var User $user_found
             */
            $user_found = $employee->employee_user()->first();
            if ($user_found) {
                $user_found->can_log_in = User::USER_STATUS_SUSPENDED;
                $user_found->save();
            }
        }
        return redirect()->route('employee.profile', ['employee_id'=>$employee_id]);
    }
}
