@php
    /**
     * @var \App\Models\User[] $users
     */
@endphp

@php
    $extra_headers = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_USER_TABLE_HEADERS, [])
@endphp

<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>First Name</th>
        <th>Last Name</th>
        @foreach($extra_headers as $header_key=> $header_html)
            <th data-extra_header="{{$header_key}}">{{$header_html}}</th>
        @endforeach
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $some_user)
        <tr>
            <td>{{$some_user->id}}</td>
            <td>{{$some_user->username}}</td>
            <td>{{$some_user->fname}}</td>
            <td>{{$some_user->lname}}</td>

            @foreach($extra_headers as $header_key=> $header_html)
                <td data-extra_header="{{$header_key}}">
                    @filter(\App\Plugins\Plugin::FILTER_USER_TABLE_COLUMN,'',$header_key,$some_user)
                </td>
            @endforeach

            <td>
                @include('admin.users.parts.edit_user_modal',compact('some_user'))

                <a href="#" data-toggle="modal" data-target="#editUserModal{{$some_user->id}}" class="btn btn-primary"
                   data-bs-toggle="modal" data-bs-target="#editUserModal{{$some_user->id}}">
                    Edit
                </a>


                <div style="display: inline-block">
                    <form method="POST" enctype="multipart/form-data" class="will-ask-first-before-deleting-user"
                          action="{{route('admin.user.delete',['user_id'=>$some_user->id])}}"
                    >
                        @csrf @method('delete')
                        <button type="submit" class="btn btn-outline-danger">
                            Delete
                        </button>

                    </form>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
