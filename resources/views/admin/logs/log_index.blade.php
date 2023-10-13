@php
    /**
     * @var \App\Models\UserLog[] $user_logs
     */
@endphp
@component('layouts.app')
    @slot('header')
        <h1>
            {{ __('All Logs') }}
        </h1>
    @endslot

    @section('main_content')

        <div class="container">

            <!-- row -->

            <div class="container">
                <ul class="timeline">
                    @forelse($user_logs as $user_log)
                        <li>
                            @include('admin.logs.log_entry',['log'=>$user_log])
                        </li>
                    @empty
                        <li>
                            <span>No logs</span>
                        </li>
                    @endforelse
                </ul>
            </div>

        </div> <!-- container -->
    @endsection
@endcomponent
