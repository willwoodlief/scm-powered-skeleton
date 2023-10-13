@php
    if (!isset($number_logs_to_show)) {$number_logs_to_show = 15;}
    $user_logs = \App\Models\UserLog::getLastLogs($number_logs_to_show)
@endphp
<ul class="timeline">
    @forelse($user_logs as $user_log)
        <li>
            @include('logs.log_entry',['log'=>$user_log])
        </li>
    @empty
        <li>
            <span>No logs</span>
        </li>
    @endforelse
</ul>
