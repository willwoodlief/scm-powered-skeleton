@php
    /**
     * @var \App\Models\Invoice $invoice
     * @var \App\Models\Project[] $all_projects
     */

@endphp

<form class="finance-hr" method="POST" enctype="multipart/form-data" action="{{route('invoice.create')}}">
    @csrf
    <div class="form-group mb-3">
        <label class="text-secondary font-w500" for="invoice-number">
            Invoice Number
            <span class="text-danger">*</span>
        </label>
        <input  type="text" class="form-control"  placeholder="ex: 12345" name="number" id="invoice-number"
                value="{{old('number',$invoice->number)}}"  >
        <x-input-error :messages="$errors->get('number')" />
    </div>

    <div class="form-group mb-3">
        <label class="text-secondary" for="invoice-amount">
            Amount
            <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <div class="input-group-text">$</div>
            <input type="number" class="form-control" placeholder="ex: 15430.49" name="amount" id="invoice-amount"
                   value="{{old('amount',$invoice->amount)}}" >
            <x-input-error :messages="$errors->get('amount')" />
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="text-secondary" for="invoice-date">
            Invoice Date
            <span class="text-danger">*</span>
        </label>
        <input type="date" class="form-control" name="date" id="invoice-date" value="{{old('amount',$invoice->date)}}">
        <x-input-error :messages="$errors->get('date')" />
    </div>

    <div class="form-group mb-3">
        <label class="text-secondary" for="project_id">
            Projects
            <span class="text-danger">*</span>
        </label>

        <select class="default-select form-control" name="project_id" id="project_id">
            @foreach($all_projects as $project)
                <option value="{{$project->id }}" @if(intval(old('project_id',$invoice->project_id)) === $project->project_id) selected @endif>
                    {{$project->project_name}}
                </option>
            @endforeach
        </select>
    </div>


    <div class="form-group mb-3">
        <label class="text-secondary" for="invoice-file">
            Attachment
            <span class="text-danger">*</span>
        </label>
        <input name="file" type="file" accept=".pdf,.txt,.doc,.xcl,.jpg,.png,.webp" id="invoice-file">
    </div>

    <button type="submit" class="btn btn-primary mb-3">Confirm</button>
</form>
