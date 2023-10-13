@php
    /**
     * @var \App\Models\Invoice $invoice
     */
@endphp

<div class="offcanvas offcanvas-end customeoff" tabindex="-1" id="offcanvas-pay-invoice{{$invoice->id}}">
    <div class="offcanvas-header">
        <h5 class="modal-title" id="#gridSystemModal1">
            Add Payment
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>

    </div>

    <div class="offcanvas-body">
        <div class="container-fluid">
            <form action="{{route('invoice.pay',["invoice_id"=>$invoice->id])}}" method="post">
                @csrf @method('patch')
                <div class="row">
                    <div class="col-xl-6 mb-3">
                        <label for="payment-{{$invoice->id}}" class="form-label">
                            Payment
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number" class="form-control" id="payment-{{$invoice->id}}" placeholder="{{$invoice->payment}}" name="payment">
                    </div>
                </div>
                <div>
                    <input type="submit" class="btn btn-primary me-1"></div>
            </form>
        </div><!-- /container -->
    </div> <!-- /offcanvas-body -->
</div> <!-- /offcanvas -->
