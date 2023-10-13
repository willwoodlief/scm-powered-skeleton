@php
    /**
     * @var \App\Models\User $some_user
     */
    if(empty($some_user)) {
        $some_user = new \App\Models\User();
    }
@endphp



@component('layouts.app')

    @section('page_title')
        Add User
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
