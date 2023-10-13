@php
    /**
     * @var \App\Models\Notification[] $notifications
     */
@endphp
@php
    $extra_headers = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_NOTIFICATION_TABLE_HEADERS, [])
@endphp
<table id="employees-tblwrapper" class="table">
    <thead>
    <tr>
        <th>@lang('ID')</th>
        <th>@lang('Employee')</th>
        <th>@lang('Title')</th>
        <th>@lang('Description')</th>
        @foreach($extra_headers as $header_key=> $header_html)
            <th data-extra_header="{{$header_key}}" >{{$header_html}}</th>
        @endforeach
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($notifications as $notification)
        @include('notifications.parts.notification_row',compact('notification','extra_headers'))
    @endforeach
    </tbody>
</table>



