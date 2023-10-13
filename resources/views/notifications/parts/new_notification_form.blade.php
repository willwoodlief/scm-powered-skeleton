@php use App\Models\Notification @endphp
@php
    /**
     * @var Notification $notification
     * @var \App\Models\Employee[] $employees
     */
@endphp
<style>
    .scm-notification-radio {
        text-align: center;
    }
    .scm-notification-radio input[type=radio] {
        transform: scale(1.5);
    }

    .scm-notification-radio span {
        font-size: 1.5em;display: inline-block; margin-left: 1rem
    }

</style>
<form method="POST" enctype="multipart/form-data" action="{{route('notifications.create')}}">
    @csrf
    <div class="row">
        <label for="notification-title" class="form-label">
            Title
            <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control" id="notification-title" name="title" placeholder="title"
               value="{{old('title',$notification->title)}}">
        <x-input-error :messages="$errors->get('title')"/>
    </div>

    <div class="row">
        <label for="notification-description" class="form-label">
            Description
            <span class="text-danger">*</span>
        </label>

        <textarea class="form-control" id="notification-description" name="description"
                  placeholder="Description">{{old('description',$notification->description)}}</textarea>
        <x-input-error :messages="$errors->get('description')"/>
    </div>

    <div class="row mt-4">
        <div class="col-6 scm-notification-radio">

            <input type="radio" name="employees"
                   value="{{Notification::NOTIFICATION_TYPE_ALL_EMPLOYEES}}"
                   checked
                   autocomplete="off"
                   id="All"
                   title="All Employees">
            <span >
                All Employees
            </span>

        </div>

        <div class="col-6 scm-notification-radio">
            <input type="radio" name="employees" value="{{Notification::NOTIFICATION_TYPE_SELECT_EMPLOYEE}}"
                   id="Select"
                   style="font-size: 2em"
                   autocomplete="off"
                   title="Some Employees">
            <span>
                Select Employees
            </span>

        </div>
        <x-input-error :messages="$errors->get('employees')"/>
    </div>

    <div class="row " id="selectemployees" style="display:none;">
        <label class="form-label" for="notification-employee-ids">
            Select Employees
            <span class="text-danger">*</span>
        </label>

        <select class="js-example-basic-multiple  form-control h-auto" id="notification-employee-ids"
                name="employee_id_selected[]" multiple="multiple"
        >

            @foreach($employees as $some_employee)
                <option value="{{$some_employee->id}}">
                    {{$some_employee->getName()}}
                </option>
            @endforeach

        </select>
    </div>

    <div class="row mt-5">
        <button type="submit" class="btn btn-primary me-1">
            Submit
        </button>
    </div>
</form>


