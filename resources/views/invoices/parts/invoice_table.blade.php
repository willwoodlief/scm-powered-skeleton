@php
    /**
     * @var \App\Models\Invoice[] $invoices
     */
@endphp
@php
    $extra_headers = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_INVOICE_TABLE_HEADERS, [])
@endphp
<table id="employees-tbl1" class="table">
    <thead>
    <tr>
        <th>@lang('ID')</th>
        <th>@lang('Invoice Number')</th>
        <th>@lang('Amount')</th>
        <th>@lang('Invoice Date')</th>
        <th>@lang('Project')</th>
        <th>@lang('Balance Owed')</th>
        <th>@lang('Status')</th>
        @foreach($extra_headers as $header_key=> $header_html)
            <th data-extra_header="{{$header_key}}" >{{$header_html}}</th>
        @endforeach
        <th></th>

    </tr>
    </thead>
    <tbody>
    @foreach($invoices as $invoice)
        @include('invoices.parts.invoice_row',compact('invoice','extra_headers'))
    @endforeach
    </tbody>
</table>



