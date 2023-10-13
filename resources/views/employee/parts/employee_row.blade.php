@php
    /**
     * @var \App\Models\Employee $employee
    * @var string[] $extra_headers
     */
@endphp

<tr>
    <td>
        <div class="products">
            <img src="{{$employee->get_image_asset_path(true)}}" class="img-thumbnail" style="width: 30px" alt="{{$employee->profile}}">
            <div>
                <h6>
                    <a href="{{route('employee.profile',['employee_id'=>$employee->id])}}" class="img-thumbnail" style="width: 30px">
                        {{$employee->first_name}} {{$employee->last_name}}
                    </a>
                </h6>
                <span>
                    {{$employee->humanRole()}}
                </span>
            </div>
        </div>
    </td>

    <td>
        @if($employee->user_id && $employee->employee_user()->first()?->can_log_in)
            <div style="text-align: center">
                <i class="fa-regular fa-square-check text-green fw-bold" style="font-size: 16px"></i>
            </div>
        @endif
    </td>

    <td>
        <span>
            {{\App\Helpers\Utilities::formatDate($employee->dob)}}
        </span>
    </td>

    <td>
        {{\App\Helpers\Utilities::format_phone_number($employee->phone) }}
    </td>

    <td>
        {{$employee->address}}
    </td>

    <td>
        {{$employee->city}}
    </td>

    <td>
        {{$employee->state}}
    </td>

    <td>
        {{$employee->zip}}
    </td>

    <td>
        <span class="text-primary">
            {{$employee->email}}
        </span>
    </td>

    <td>
        <span>
            {{\App\Helpers\Utilities::formatDate($employee->hire_date)}}
        </span>
    </td>

    <td>
        <span class="badge @if($employee->status === \App\Models\Employee::STATUS_ACTIVE )badge-success @else  badge-danger @endif light border-0">
            {{$employee->humanStatus()}}
        </span>
    </td>

    @foreach($extra_headers as $header_key=> $header_html)
        <td data-extra_header="{{$header_key}}">
            @filter(\App\Plugins\Plugin::FILTER_EMPLOYEE_TABLE_COLUMN,'',$header_key,$employee)
        </td>
    @endforeach
</tr>
