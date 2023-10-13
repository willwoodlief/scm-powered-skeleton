@php
    /**
     * @var \App\Models\Invoice[] $invoices
     */
@endphp

@component('layouts.app')

    @section('page_title')
        Accounting
    @endsection

    @section('main_content')

        <div class="container">
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-xxl-4">
                        <div class="card h-auto">
                            <div class="card-header">
                                <h4 class="heading mb-0">Add New Invoice </h4>
                            </div>
                            <div class="card-body">
                                @include('invoices.parts.invoice_form',['invoice'=>new \App\Models\Invoice()])
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9 col-xxl-8">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive active-projects manage-client">
                                    <div class="tbl-caption">
                                        <h4 class="heading mb-0">Finance</h4>
                                    </div>
                                    @include('invoices.parts.invoice_table')
                                </div>
                            </div>
                        </div> <!-- /card -->
                    </div> <!-- /col -->
                </div> <!-- /row -->
            </div> <!-- /container-fluid -->
        </div> <!-- /container -->

    @endsection
@endcomponent
