@php
    /**
     * @var \App\Models\Notification $notification
     */
@endphp

<tr>
    <td>
        {{$notification->id}}
    </td>

    <td>
        {{$notification->employee_id}}
    </td>

    <td>
        {{$notification->title}}
    </td>

    <td>
        {{$notification->description}}
    </td>
    @foreach($extra_headers as $header_key=> $header_html)
        <td data-extra_header="{{$header_key}}">
            @filter(\App\Plugins\Plugin::FILTER_NOTIFICATION_TABLE_COLUMN,'',$header_key,$notification)
        </td>
    @endforeach

    <td>
        <form method="POST" enctype="multipart/form-data" class="will-ask-first-before-deleting-notification"
              action="{{route('notifications.delete',['notification_id'=>$notification->id])}}"
        >
            @csrf @method('delete')
            <button type="submit" class="btn btn-outline-danger">
                Delete
            </button>

        </form>
    </td>

</tr>
