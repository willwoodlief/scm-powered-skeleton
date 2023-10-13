@php
    /**
     * @var \App\Models\ConstructionExpense[] $expenses
     */
@endphp
@php
    $extra_headers = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_EXPENSES_TABLE_HEADERS, [])
@endphp
<table id="employees-tblwrapper" class="table">
    <thead>
    <tr>
        <th>@lang('ID')</th>
        <th>@lang('Transaction Type')</th>
        <th>@lang('Date')</th>
        <th>@lang('Business')</th>
        <th>@lang('Description')</th>
        <th>@lang('Amount')</th>
        <th>@lang('Employee')</th>
        <th>@lang('Project')</th>
        <th>@lang('Edit/Delete')</th>
        @foreach($extra_headers as $header_key=> $header_html)
            <th data-extra_header="{{$header_key}}" >{{$header_html}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($expenses as $expense)
        @include('expenses.parts.expense_row',compact('expense','extra_headers'))
    @endforeach
    </tbody>
</table>


