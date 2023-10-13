@php
    /**
     * @var \App\Models\User $some_user
     */
@endphp

<div class="modal fade" id="editUserModal{{$some_user->id}}" tabindex="-1" role="dialog" aria-labelledby="editUserModal{{$some_user->id}}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModal{{$some_user->id}}Label">Edit {{$some_user->getName()}} </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('admin.users.parts.user_form',["some_user",$some_user])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
