@component('layouts.app')
    @slot('header')
        <h1>
            {{ __('Tasks') }}
        </h1>
    @endslot

    @include('tasks.todo_list')
@endcomponent
