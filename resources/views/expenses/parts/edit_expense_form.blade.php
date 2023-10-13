@php
    /**
     * @var \App\Models\ConstructionExpense $expense
     * @var \App\Models\Project[] $all_projects
     */
@endphp

<h1>Edit Transaction</h1>
<form method="POST" enctype="multipart/form-data" action="{{route('transactions.update',['expense_id'=>$expense->id])}}">
    @csrf @method('patch')
    <div class="form-row">
        <div class="form-column">
            <label for="transaction_type">Transaction Type:</label>
            <input type="text" name="transaction_type" id="transaction_type"
                   value="{{old('date',$expense->transaction_type)}}" required>
            <x-input-error :messages="$errors->get('transaction_type')"/>
        </div>

        <div class="form-column">
            <label for="date">Date:</label>
            <input type="date" name="date" id="date" value="{{old('date',$expense->date)}}" required>
            <x-input-error :messages="$errors->get('date')"/>
        </div>

        <div class="form-column">
            <label for="business">Business:</label>
            <input type="text" name="business" id="business" value="{{old('business',$expense->business)}}" required>
            <x-input-error :messages="$errors->get('business')"/>
        </div>
        <div class="form-column">
            <label for="description">Description:</label>
            <input type="text" name="description" id="description" value="{{old('description',$expense->description)}}"  required>
            <x-input-error :messages="$errors->get('description')"/>
        </div>

        <div class="form-column">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" min="0.01" name="amount" id="amount" value="{{old('amount',$expense->amount)}}" required>
            <x-input-error :messages="$errors->get('amount')"/>
        </div>

        <div class="form-column">
            <label for="employee_id">Employee:</label>
            <input type="text" name="employee_id" id="employee_id" value="{{old('employee_id',$expense->employee_id)}}" required>
            <x-input-error :messages="$errors->get('employee_id')"/>
        </div>

        <div class="form-column">
            <label for="expense-project-id">Project:</label>

            <select class="default-select form-control" name="project_id" id="expense-project-id">
                @foreach($all_projects as $project)
                    <option value="{{$project->id }}" @if(intval(old('project_id',$expense->project_id)) === $project->project_id) selected @endif>
                        {{$project->project_name}}
                    </option>
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('project_id')"/>

        </div>
    </div>

    <div class="form-row">
        <div class="form-column">
            <input type="submit" class="btn btn-primary mt-2" value="Save">
        </div>
    </div>

</form>

