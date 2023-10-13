@php
    /**
    * @var \App\Models\Project[] $active_projects
     */
@endphp
<div class="card-body p-0">
    <div class="table-responsive active-projects">
        <div class="tbl-caption">
            <h4 class="heading mb-0">Active Projects</h4>
        </div>

        @php
            $extra_headers = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_ACTIVE_PROJECT_TABLE_HEADERS, [])
        @endphp

        <table id="projects-tbl" class="table">
            <thead>
            <tr>
                <th>Project Name</th>
                <th>Contractor</th>
                <th>Foreman</th>
                <th>Due Date</th>
                <th>Status</th>
                @foreach($extra_headers as $header_key=> $header_html)
                    <th data-extra_header="{{$header_key}}" >{{$header_html}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach ($active_projects as $project)

            <tr>
                <td>
                    <a href="{{route('project.view',['project_id'=>$project->id])}}">
                        {{$project->project_name}}
                    </a>
                </td>
                <td>
                    <a href="{{route('contractor.view',['contractor_id'=>$project->project_contractor->id])}}">
                        {{$project->project_contractor->getName()}}
                    </a>

                </td>
                <td>
                    @forelse($project->project_assigns as $assigned_foreman)
                        <ul>
                            <li>
                                {{$assigned_foreman->assigned_employee->getName()}}
                            </li>
                        </ul>
                    @empty
                        <span>No Foreman</span>
                    @endforelse

                <td>
                    {{\App\Helpers\Utilities::formatDate($project->end_date)}}
                </td>

                <td>
                    @if ($project->status === App\Models\Project::STATUS_IN_PROGRESS)
                        <span class="badge bg-warning">In Progress</span>
                    @elseif($project->status === App\Models\Project::STATUS_COMPLETED)
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-danger">Not Started</span>
                    @endif
                </td>

                @foreach($extra_headers as $header_key=> $header_html)
                    <td data-extra_header="{{$header_key}}">
                        @filter(\App\Plugins\Plugin::FILTER_ACTIVE_PROJECT_TABLE_COLUMN,'',$header_key,$project)
                    </td>
                @endforeach
            </tr>
            @endforeach
            </tbody>

        </table>
    </div>
</div>
