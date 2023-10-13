@php
    /**
     * @var \App\Models\Project $project
     */
@endphp

@if($project->status === \App\Models\Project::STATUS_NOT_STARTED)
    <div class="widget-stat card bg-danger">
        <div class="card-body p-4">
            <div class="media">
                <span class="me-3">
                    <i class="las la-stopwatch"></i>
                </span>
                <div class="media-body text-white">
                    <p class="mb-1">Project Status</p>
                    <h3 class="text-white">Not Started</h3>
                    <div class="progress mb-2 bg-primary">
                        <div class="progress-bar progress-animated bg-white"
                             style="width: 1%"></div>
                    </div>
                    <small>Start Date: <?= $project['start_date']; ?></small>
                </div>
            </div>
        </div>
    </div>
@elseif($project->status === \App\Models\Project::STATUS_IN_PROGRESS)

    <div class="widget-stat card bg-warning">
        <div class="card-body p-4">
            <div class="media">
                <span class="me-3">
                    <i class="las la-hard-hat"></i>
                </span>
                <div class="media-body text-white">
                    <p class="mb-1">Project Status</p>
                    <h3 class="text-white">In Progress</h3>
                    <div class="progress mb-2 bg-primary">
                        <div class="progress-bar progress-animated bg-white"
                             style="width: 50%"></div>
                    </div>
                    <small>Due Date: {{\App\Helpers\Utilities::formatDate($project->end_date)}}</small>
                </div>
            </div>
        </div>
    </div>
@elseif($project->status === \App\Models\Project::STATUS_COMPLETED)

    <div class="widget-stat card bg-success">
        <div class="card-body p-4">
            <div class="media">
                <span class="me-3">
                    <i class="las la-handshake"></i>
                </span>
                <div class="media-body text-white">
                    <p class="mb-1">Project Status</p>
                    <h3 class="text-white">Complete</h3>
                    <div class="progress mb-2 bg-primary">
                        <div class="progress-bar progress-animated bg-white"
                             style="width: 100%"></div>
                    </div>
                    <small>Great Work!</small>
                </div>
            </div>
        </div>
    </div>

@endif
