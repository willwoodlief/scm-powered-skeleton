<form action="{{route('tasks.create')}}" method="post">
    @csrf
    <div class="row">
        <div class="col-xl-6 mb-3">
            <label for="todo" class="form-label">
                Task
                <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" id="todo" placeholder="Title" name="item">
            <x-input-error :messages="$errors->get('item')" />
        </div>
    </div>
    <div>
        <input type="submit" class="btn btn-primary me-1">
    </div>
</form>
