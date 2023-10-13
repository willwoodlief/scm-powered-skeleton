@php

    /**
     * @var \App\Models\ConstructionExpense[] $expenses
     */
@endphp

@component('layouts.app')

    @section('page_title')
        Transactions
    @endsection

    @section('main_content')

        <div class="container">
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive active-projects style-1">
                                    <div class="tbl-caption">
                                        <h4 class="heading mb-0">Transactions</h4>
                                        <div>
                                            <a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" href="#offcanvas-new-transaction" role="button" aria-controls="offcanvas-new-transaction">
                                                + Add Transaction
                                            </a>
                                        </div>
                                    </div>

                                    @include('expenses.parts.expense_table',compact('expenses'))
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

            </div> <!-- /container-fluid-->

            <div class="offcanvas offcanvas-end customeoff" tabindex="-1" id="offcanvas-new-transaction">
                <div class="offcanvas-header">
                    <h5 class="modal-title" id="#gridSystemModal">Add Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="offcanvas-body">
                    <div class="container-fluid">
                        @include('expenses.parts.new_expense_form',['expense'=>new \App\Models\ConstructionExpense()])
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endcomponent
