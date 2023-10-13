@php
    /**
     * @var \App\Models\ToDo[] $todo_list
     */
@endphp

<div class="">
    @forelse (App\Models\ToDo::getUserTodo(\Illuminate\Support\Facades\Auth::id()) as $todo)
    <div class="sub-card draggable-handle draggable">
        <div class="d-items" style="padding: 5px;">
            <div class="d-flex justify-content-between flex-wrap">
                <div class="d-items-2">
                    <div>
                        <div class="form-check custom-checkbox">
                            <input type="checkbox" class="form-check-input todo-checkbox" id="todo-{{$todo->id}}" data-id="{{$todo->id}}" required>
                            <label class="form-check-label" for="todo-{{$todo->id}}">
                                {{$todo->item}}
                            </label>
                        </div>
                        <span style="font-size:10px">
                            {{$todo->date}}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @empty
        <span style="padding:4px;display: block; text-align: center">
            You're all caught up! No to do items.
        </span>
    @endforelse
</div>

