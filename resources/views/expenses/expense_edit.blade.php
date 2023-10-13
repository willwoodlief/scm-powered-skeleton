@php
    /**
     * @var \App\Models\ConstructionExpense $expense
     * @var \App\Models\Project[] $all_projects
     */
@endphp
@component('layouts.app')

    @section('page_title')
        New Transaction
    @endsection

    @section('main_content')
        <div class="container">
            <!-- row -->

            <div class="container-fluid">

                <div class="col-12 col-lg-8 col-xl-6 col-xxl-4">
                    <div class="card">
                        <div class="card-body">
                            @include('expenses.parts.edit_expense_form',['expense'=>$expense])
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endsection
@endcomponent
