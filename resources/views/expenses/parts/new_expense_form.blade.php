@php
    /**
     * @var \App\Models\ConstructionExpense $expense
     * @var \App\Models\Project[] $all_projects
     */
@endphp

<h1>Add Transaction</h1>

<form method="POST" action="{{route('transactions.create')}}">
    @csrf
    <div class="form-row">
        <div class="form-column">
            <label for="transaction_type">Transaction Type:</label>
            <select name="transaction_type" id="transaction_type" class="form-control">
                <option value="Fuel">Fuel</option>
                <option value="Jobsite Expense">Jobsite Expense</option>
                <option value="Disposal">Disposal</option>
                <option value="General Tools">General Tools</option>
                <option value="Rentals">Rentals</option>
                <option value="Vehicle">Vehicle</option>
                <option value="Supplies">Office Supplies</option>
            </select>
            <x-input-error :messages="$errors->get('transaction_type')"/>
        </div>
        <div class="form-column">
            <label for="date">Date:</label>
            <input type="date" name="date" id="date" required value="{{old('date',$expense->date)}}" class="form-control">
            <x-input-error :messages="$errors->get('date')"/>
        </div>
        <div class="form-column">
            <label for="business">Business Name:</label>
            <input type="text" name="business" id="business" required value="{{old('business',$expense->business)}}" class="form-control">
            <x-input-error :messages="$errors->get('business')"/>
        </div>
        <div class="form-column">
            <label for="description">Description:</label>
            <input type="text" name="description" id="description" required value="{{old('description',$expense->description)}}" class="form-control">
            <x-input-error :messages="$errors->get('description')"/>
        </div>
        <div class="form-column">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" min="0.01" name="amount" id="amount" required value="{{old('amount',$expense->amount)}}" class="form-control">
            <x-input-error :messages="$errors->get('amount')"/>
        </div>
        <div class="form-column">
            <label for="employee_id">Employee Name:</label>
            <input type="text" name="employee_id" id="employee_id" required value="{{old('employee_id',$expense->employee_id)}}" class="form-control">
            <x-input-error :messages="$errors->get('employee_id')"/>
        </div>

        <div class="form-column">
            <label for="expense-project-id">Project:</label>

            <select class="default-select form-control" name="project_id" id="expense-project-id" class="form-control">
                @foreach($all_projects as $project)
                    <option value="{{$project->id }}" @if(intval(old('project_id',$expense->project_id)) === $project->project_id) selected @endif>
                        {{$project->project_name}}
                    </option>
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('project_id')"/>
        </div>

    </div>

    <br>
    <input type="submit" class="btn btn-primary mt-2" value="Add Transaction">
</form>


