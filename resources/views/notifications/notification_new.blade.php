@php use App\Models\Notification @endphp
@php
    /**
     * @var Notification $notification
     * @var \App\Models\Employee[] $employees
     */
@endphp
@component('layouts.app')
    @section('page_title')
        Make Notification
    @endsection

    @section('main_content')
        <div class="container">
            <!-- row -->

            <div class="container-fluid">
                @include('notifications.parts.new_notification_form', ['notification'=>new Notification(),'employees' => $employees])
            </div>
        </div> <!-- /container -->
    @endsection
@endcomponent

