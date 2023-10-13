@php
    use \App\Models\UserRole;
    use \Illuminate\Support\Facades\Auth;
        /**
         * @var \App\Models\Employee $employee
         */
        if ( !(Auth::user()->has_role(UserRole::USER_ROLE_HR))  ) {
            return;
        }
@endphp

<div class="card">
    <div class="card-header pb-0 border-0">
        <h3 class="card-title ">Employee web access</h3>
    </div> <!-- /card-header -->

    <div class="card-body">
        <form  method="POST" enctype="multipart/form-data" action="{{route('employee.hr.web_access',['employee_id'=>$employee->id])}}">
            @csrf
            <div class="row">


                <div class="col-6 col-md-3 mb-3 mb-md-0" >
                    <label for="employee_password-{{$employee->id}}" class="mb-md-0">
                        Password
                    </label>
                    <input id="employee_password-{{$employee->id}}"
                           type="password"
                           name="employee_password"
                           autocomplete="employee_password"
                           title="Password"
                           class="form-control" />
                </div>

                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <label for="employee_password_confirm-{{$employee->id}}" class="mb-md-0">
                        Confirm Password
                    </label>
                    <input id="employee_password_confirm-{{$employee->id}}"
                           type="password"
                           name="employee_password_confirm"
                           autocomplete="off"
                           title="Password"
                           class="form-control" />
                </div>

                <div class="col-6 col-md-3 order-md-first">
                    <input type="checkbox"
                           class="form-check-input todo-checkbox"
                           id="employee-has-access-{{$employee->id}}"
                           name="employee_has_web_access"
                           autocomplete="off"
                           value="1"
                           @if($employee->user_id && $employee->employee_user()->first()?->can_log_in) CHECKED @endif
                    >
                    <label class="form-check-label" for="employee-has-access-{{$employee->id}}">
                        Access
                    </label>
                </div>

                <div class="col-6 col-md-3 ">
                    <button class="btn btn-primary mt-md-4" type="submit">
                        Change Access
                    </button>

                </div>
            </div>
        </form>
    </div> <!-- /card-body -->
</div>
