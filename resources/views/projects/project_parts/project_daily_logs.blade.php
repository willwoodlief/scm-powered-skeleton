@php
    /**
     * @var \App\Models\Project $project
     */
@endphp
<div class="row">
    <div class="col-xl-5 bst-seller">
        <div class="card">

            <div class="active-projects style-1">

                <div class="tbl-caption">
                    <h4 class="heading mb-0">Daily Logs</h4>
                    <div>
                        <a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas"
                           href="#scm-create-daily-log-dialog" role="button" aria-controls="scm-create-daily-log-dialog"
                        >
                            + Add Log
                        </a>

                    </div>
                </div>
                <div class="card-body" style="overflow-x: scroll;max-height: 350px">
                    <div class="profile-interest">
                        @forelse($project->project_logs as $log)

                            <h5 class="text-primary d-inline">
                                Daily Log - {{gmdate('m/d/Y', $log->timestampss)}}
                            </h5>
                            <p>
                                {{$log->content}}
                                - posted by
                                {{$log->getWriter()->getName()}}
                            </p>
                            <div class="row mt-4 sp4" id="lightgallery">
                                @forelse($log->daily_log_files as $photo)

                                    <a href="{{asset($photo->calculate_relative_path())}}"
                                       data-exthumbimage="{{asset($photo->calculate_relative_path(true))}}"
                                       data-src="{{asset($photo->calculate_relative_path())}}"
                                       class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6"
                                       style="width: 10%"
                                    >
                                        <img src="{{asset($photo->calculate_relative_path())}}" alt="" class="img-fluid rounded">
                                    </a>
                                @empty
                                    <span>
                                        No photos for this log.
                                    </span>
                                @endforelse

                            </div>
                            <hr />
                        @empty
                            <span>
                                No logs created
                            </span>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('projects.project_parts.add_daily_log',compact('project'))
