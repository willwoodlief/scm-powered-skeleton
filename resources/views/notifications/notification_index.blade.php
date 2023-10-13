@php
    /**
     * @var \App\Models\Notification[] $notifications
     * @var \App\Models\Employee[] $employees
     */
@endphp
@component('layouts.app')

    @section('page_title')
        Notifications
    @endsection

    @section('main_content')
        <div class="container">
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive active-projects style-1">
                                    <div class="tbl-caption">
                                        <h4 class="heading mb-0">Notifications</h4>
                                        <div>
                                            <a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" href="#offcanvas-new-notification-form" role="button" aria-controls="offcanvas-new-notification-form">
                                                + Add Notifications
                                            </a>
                                        </div>
                                    </div>
                                    @include('notifications.parts.notification_list',['notifications'=>$notifications])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- /container -->


        <div class="offcanvas offcanvas-end customeoff" tabindex="-1" id="offcanvas-new-notification-form">
            <div class="offcanvas-header">
                <h5 class="modal-title" id="#gridSystemModal">Add Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <div class="container-fluid">
                    @include('notifications.parts.new_notification_form', ['notification'=>new \App\Models\Notification(),'employees' => $employees])
                </div>
            </div>
        </div>

    @endsection
@endcomponent
