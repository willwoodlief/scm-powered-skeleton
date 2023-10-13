@php
    /**
     * @var \App\Models\User $some_user
     */
@endphp

@component('layouts.app')

    @section('page_title')
        Edit User {{$some_user->getName()}}
    @endsection

    @section('main_content')

        <div class="container">

            <!-- row -->

            <div class="container-fluid">

                @include('admin.users.parts.user_form',compact('some_user'))

            </div> <!-- container-fluid -->
        </div> <!-- container -->
    @endsection
@endcomponent
