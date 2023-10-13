@php
    /**
     * @var \App\Models\Project $project
     */
@endphp

<div class="card">
    <div class="card-header border-0 pb-1">
        <h4 class="heading mb-0">Upcoming Schedules</h4>
    </div>
    <div class="card-body schedules-cal p-2">
        <input type="text" class="form-control d-none" id="datetimepicker1" title="">
        <div class="events">
            <h6>events</h6>
            <div class="dz-scroll event-scroll">
                <div class="event-media">
                    <div class="d-flex align-items-center">
                        @php $formatted_start_date = date("j M", intval($project->start_date )) @endphp

                        <div class="event-box">
                            <h5 class="mb-0"> {{  substr($formatted_start_date, 0, 2) }} </h5>
                            <span> {{substr($formatted_start_date, 3) }} </span>
                        </div>

                        <div class="event-data ms-2">
                            <h5 class="mb-0"><a href="javascript:void(0)">Start Date</a></h5>
                            <span></span>
                        </div>
                    </div>
                    <span class="text-secondary">12:05 PM</span>
                </div>
                <div class="event-media">
                    <div class="d-flex align-items-center">
                        @php $formatted_end_date = date("j M", strtotime($project->end_date)); @endphp

                        <div class="event-box">';
                            <h5 class="mb-0">{{ substr($formatted_end_date, 0, 2) }}</h5>
                            <span> {{ substr($formatted_end_date, 2) }}</span>
                        </div>

                        <div class="event-data ms-2">
                            <h5 class="mb-0"><a href="javascript:void(0)">Deadline</a></h5>
                            <span></span>
                        </div>
                    </div>
                    <span class="text-secondary">12:05 PM</span>
                </div>
                <div class="event-media">
                    <div class="d-flex align-items-center">
                        <div class="event-box">
                            <h5 class="mb-0">20</h5>
                            <span>Feb</span>
                        </div>
                        <div class="event-data ms-2">
                            <h5 class="mb-0"><a href="javascript:void(0)">Invoice Sent</a></h5>
                            <span>$128,000</span>
                        </div>
                    </div>
                    <span class="text-secondary">12:05 PM</span>
                </div>
            </div>
        </div>
    </div>
</div>
