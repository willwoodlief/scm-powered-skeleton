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

<div class="my-post-content pt-3">
    <div class="">
        @include('employee.hr.web_access',['employee'=>$employee])
    </div>
</div>
