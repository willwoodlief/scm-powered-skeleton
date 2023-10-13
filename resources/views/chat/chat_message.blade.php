@php
    /**
     * @var \App\Models\ChatMessage $chat
     */
@endphp

<div class="d-flex justify-content-end mb-4">

    <div class="msg_cotainer_send">
        {{$chat->message}}
        <span class="msg_time_send">

            {{\App\Helpers\Utilities::formatDate($chat->created_at)}}

            (
            <a href="{{route('employee.profile',['employee_id'=>$chat->chat_user->getID()])}}">{{$chat->chat_user->getName()}}</a>
            )
        </span>
    </div>

    <div class="img_cont_msg">
        <img src="{{$chat->chat_user->get_image_asset_path(true)}}" class="img-thumbnail" style="width: 30px" alt="icon">
    </div>
</div>

