@php
    /**
     * @var \App\Models\UserLog $log
     */
@endphp

<div class="timeline-panel">
    <div class="media me-2">
        <img alt="image" class="img-thumbnail" style="width: 30px" src="{{asset($log->log_user->get_image_relative_path(true))}}">
    </div>
    <div class="media-body">
        <h6 class="mb-1">
            {{$log->log_user->getName()}}
            {{$log->action}}
        </h6>

        <small class="d-block">
            {{\App\Helpers\Utilities::formatDate($log->created_at)}}
        </small>
    </div>
</div>
