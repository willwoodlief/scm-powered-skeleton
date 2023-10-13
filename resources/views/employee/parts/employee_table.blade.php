@php
    /**
     * @var \App\Models\Employee[] $employees
     */
@endphp

@php
    $extra_headers = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_EMPLOYEE_TABLE_HEADERS, [])
@endphp

<table id="employees-tbl" class="table">
    <thead>
    <tr>
        <th>@lang('Employee Name')</th>
        <th>@lang('Access')</th>
        <th>@lang('Birthdate')</th>
        <th>@lang('Phone')</th>
        <th>@lang('Address')</th>
        <th>@lang('City')</th>
        <th>@lang('State')</th>
        <th>@lang('Zip')</th>
        <th>@lang('Email')</th>
        <th>@lang('Hire Date')</th>
        <th>@lang('Status')</th>
        @foreach($extra_headers as $header_key=> $header_html)
            <th data-extra_header="{{$header_key}}" >{{$header_html}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($employees as $employee)
        @include('employee.parts.employee_row',compact('employee','extra_headers'))
    @endforeach
    </tbody>
</table>
