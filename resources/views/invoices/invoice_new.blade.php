@component('layouts.app')

    @section('page_title')
        Add Invoice
    @endsection

    @section('main_content')

        <div class="container">
            <!-- row -->

            <div class="container-fluid">
                @include('invoices.parts.invoice_form',['invoice'=>new \App\Models\Invoice()])
            </div> <!-- /container-fluid -->
        </div> <!-- /container -->

    @endsection
@endcomponent



