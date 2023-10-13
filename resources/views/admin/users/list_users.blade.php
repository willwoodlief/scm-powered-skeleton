@php
    /**
     * @var \App\Models\User[] $users
     */
@endphp

@component('layouts.app')

    @section('page_title')
        Users
    @endsection

    @section('main_content')

        <div class="container">

            <!-- row -->

            <div class="container-fluid">
                <h3>
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        Add New User
                    </a>
                </h3>
                @include('admin.users.parts.user_list')
            </div> <!-- container-fluid -->
        </div> <!-- container -->



        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Create New User </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('admin.users.parts.user_form',['some_user'=> new \App\Models\User()])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    @endsection
@endcomponent

