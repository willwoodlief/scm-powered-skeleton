@php
    /**
     * @var \App\Models\ChatMessage $chat
     */
@endphp
<div class="d-flex justify-content-start mb-4">
    <div class="img_cont_msg">
        <img src="{{$chat->chat_user->get_image_asset_path(true)}}" class="img-thumbnail" style="width: 30px" alt="icon">
    </div>
    <div class="msg_cotainer">
        {{$chat->message}}
        <span class="msg_time">
             (
            <a href="{{route('employee.profile',['employee_id'=>$chat->chat_user->getID()])}}" style="color: #fff">{{$chat->chat_user->getName()}}</a>
            )
            {{\App\Helpers\Utilities::formatDate($chat->created_at)}}
        </span>
    </div>
</div>
