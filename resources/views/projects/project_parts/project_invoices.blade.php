@php
    /**
     * @var \App\Models\Project $project
     */
@endphp
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive active-projects style-1">
            <div class="tbl-caption">
                <h4 class="heading mb-0">Invoices</h4>
                <div>
                    <a class="btn btn-primary btn-sm" href="{{route('invoice.new')}}" role="button">
                        + Add Invoice
                    </a>

                </div>
            </div>

            @php
                $extra_headers = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_PROJECT_INVOICE_TABLE_HEADERS, [])
            @endphp

            <table id="projects-tbl" class="table">
                <thead>
                <tr>
                    <th>Number</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    @foreach($extra_headers as $header_key=> $header_html)
                        <th data-extra_header="{{$header_key}}">{{$header_html}}</th>
                    @endforeach
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                @forelse($project->project_invoices as $invoice)
                    <tr>
                        <td>{{$invoice->number}}</td>
                        <td>{{number_format($invoice->amount, 2)}}</td>
                        <td>{{\App\Helpers\Utilities::formatDate($invoice->date)}}</td>
                        <td>
                            @if ($invoice->status === \App\Models\Invoice::STATUS_PAID)
                                <span style="color:green">PAID</span>
                            @else
                                <span style="color:red;border:1px solid;padding:1px">OPEN</span>
                            @endif
                        </td>

                        @foreach($extra_headers as $header_key=> $header_html)
                            <td data-extra_header="{{$header_key}}">
                                @filter(\App\Plugins\Plugin::FILTER_PROJECT_INVOICE_TABLE_COLUMN,'',$header_key,$invoice)
                            </td>
                        @endforeach

                        <td>
                            @include('invoices.parts.invoice_pay',compact('invoice'))
                            <div style="font-size: 20px">
                                @if($invoice->status === App\Models\Invoice::STATUS_OPEN)
                                    <a href="#offcanvas-pay-invoice{{$invoice->id}}" data-bs-toggle="offcanvas"
                                       role="button" aria-controls="offcanvas-pay-invoice{{$invoice->id}}"
                                    >
                                        <i class="las la-money-check-alt"></i>
                                    </a>
                                @endif
                                @if($invoice->file)
                                    <a href="{{asset($invoice->get_invoice_directory().DIRECTORY_SEPARATOR . $invoice->file)}}"
                                       download>
                                        <i class="las la-file-download"></i>
                                    </a>
                                @endif

                                <form method="POST" enctype="multipart/form-data"
                                      class="will-ask-first-before-deleting-invoice"
                                      action="{{route('invoice.delete',['invoice_id'=>$invoice->id])}}"
                                >
                                    @csrf @method('delete')
                                    <input type="hidden" name="invoice_file_name" value="{{$invoice->file}}">
                                    <button type="submit" class="btn btn-rounded btn-outline-danger border-0">
                                        <i class="las la-times-circle"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td></td>
                        <td>No Invoices</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforelse

                </tbody>

            </table>

        </div>
    </div>
</div>
