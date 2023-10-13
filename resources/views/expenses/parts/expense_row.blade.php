@php
    /**
     * @var \App\Models\ConstructionExpense $expense
     */
@endphp

<tr>
    <td>
        {{$expense->id}}
    </td>

    <td>
        {{$expense->transaction_type}}

    </td>

    <td>
        {{\App\Helpers\Utilities::formatDate($expense->date)}}
    </td>

    <td>
        {{$expense->business}}
    </td>

    <td>
        {{$expense->description}}
    </td>

    <td>
        {{\App\Helpers\Utilities::formatMoney($expense->amount)}}
    </td>

    <td>
        {{$expense->employee_id}}
    </td>

    <td>
        <a href="{{route('project.view',['project_id'=>$expense->project_id])}}">
            {{$expense->expense_project?->project_name}}
        </a>
    </td>

    <td>
        <a href="{{route('transactions.edit',['expense_id'=>$expense->id])}}" class="btn btn-outline-info">
            Edit
        </a>

        <div style="display: inline-block">
            <form method="POST" enctype="multipart/form-data" class="will-ask-first-before-deleting-expense"
                  action="{{route('transactions.delete',['expense_id'=>$expense->id])}}"
            >
                @csrf @method('delete')
                <button type="submit" class="btn btn-outline-danger">
                    Delete
                </button>

            </form>
        </div>

    </td>

    @foreach($extra_headers as $header_key=> $header_html)
        <td data-extra_header="{{$header_key}}">
            @filter(\App\Plugins\Plugin::FILTER_EXPENSES_TABLE_COLUMN,'',$header_key,$expense)
        </td>
    @endforeach

</tr>
