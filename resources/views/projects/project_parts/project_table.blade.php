@php
    /**
     * @var \App\Models\Project[] $projects
     */
@endphp

@php
    $extra_headers = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_PROJECT_TABLE_HEADERS, [])
@endphp

<table id="employees-tblwrapper" class="table">
    <thead>
    <tr>
        <th>@lang('Project Name')</th>
        <th>@lang('Start Date')</th>
        <th>@lang('End Date')</th>
        <th>@lang('Contractor')</th>
        <th>@lang('Budget')</th>
        <th>@lang('Status')</th>
        @foreach($extra_headers as $header_key=> $header_html)
            <th data-extra_header="{{$header_key}}" >{{$header_html}}</th>
        @endforeach
        <th>@lang('Actions')</th>

    </tr>
    </thead>
    <tbody>
    @foreach($projects as $project)
        @include('projects.project_parts.project_row',compact('project','extra_headers'))
    @endforeach
    </tbody>
</table>


