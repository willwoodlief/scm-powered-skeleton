
@forelse(\App\Models\Project::getProjectsStartingThisMonth() as $project)
    @php $start_date = new DateTime($project->start_date) @endphp

    <div class="event-media">
        <div class="d-flex align-items-center">
            <div class="event-box">
                <h5 class="mb-0">{{$start_date->format('j')}}</h5>
                <span>{{$start_date->format('D')}}</span>
            </div>
            <div class="event-data ms-2">
                <h5 class="mb-0">
                    <a href="{{route('project.view',['project_id'=>$project->id])}}">
                        {{$project->project_name}}
                    </a>
                </h5>
                <span>
                    {{$project->project_contractor->getName()}}
                </span>
            </div>
        </div>
        <span class="text-secondary">Starting this month!</span>
    </div>
@empty

    No projects starting this month

@endforelse
