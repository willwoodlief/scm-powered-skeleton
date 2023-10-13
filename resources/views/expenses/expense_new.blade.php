@php
    /**
     * @var \App\Models\ConstructionExpense $expense
     * @var \App\Models\Project[] $all_projects
     */
@endphp
@component('layouts.app')

    @section('page_title')
        Add Transaction
    @endsection

    @section('main_content')
        <div class="container">
            <!-- row -->

            <div class="container-fluid">
                @include('expenses.parts.new_expense_form',['expense'=>new \App\Models\ConstructionExpense()])
            </div>
        </div>
    @endsection
@endcomponent
