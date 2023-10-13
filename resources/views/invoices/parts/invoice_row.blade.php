@php
    /**
     * @var \App\Models\Invoice $invoice
     */
@endphp

<tr>
    <td>
        {{$invoice->id}}
    </td>

    <td>
        {{$invoice->number}}

    </td>

    <td>
        {{$invoice->amount}}
    </td>

    <td>
        {{\App\Helpers\Utilities::formatDate($invoice->date)}}
    </td>

    <td>
        <a href="{{route('project.view',['project_id'=>$invoice->project_id])}}">
            {{$invoice->invoice_project?->project_name}}
        </a>

    </td>

    <td>
        {{\App\Helpers\Utilities::formatMoney($invoice->payment)}}
    </td>

    <td>
        @if($invoice->status === App\Models\Invoice::STATUS_PAID)
            <span style="color:green">PAID</span>
        @else
            <span style="color:red;border:1px solid;padding:1px">OPEN</span>
        @endif
    </td>

    @foreach($extra_headers as $header_key=> $header_html)
        <td data-extra_header="{{$header_key}}">
            @filter(\App\Plugins\Plugin::FILTER_INVOICE_TABLE_COLUMN,'',$header_key,$invoice)
        </td>
    @endforeach

    <td>
        @include('invoices.parts.invoice_pay',compact('invoice'))
        <span>
            <span style="font-size: 20px">
                @if($invoice->status === App\Models\Invoice::STATUS_OPEN)
                    <a href="#offcanvas-pay-invoice{{$invoice->id}}" data-bs-toggle="offcanvas"
                       role="button" aria-controls="offcanvas-pay-invoice{{$invoice->id}}"
                    >
                        <i class="las la-money-check-alt text-green"></i>
                    </a>
                @endif


                @if($invoice->file)
                    <a href="{{asset($invoice->calculate_relative_path())}}" download>
                        <i class="las la-file-download"></i>
                    </a>
                @endif


                <form method="POST" enctype="multipart/form-data"
                      class="will-ask-first-before-deleting-invoice d-inline-block"
                      action="{{route('invoice.delete',['invoice_id'=>$invoice->id])}}"
                >
                    @csrf @method('delete')
                    <button type="submit" class="btn btn-outline-danger border-0">
                        <i class="las la-times-circle"></i>
                    </button>

                </form>
            </span>
        </span>
    </td>


</tr>
