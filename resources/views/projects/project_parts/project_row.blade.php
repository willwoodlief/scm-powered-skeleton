@php
    /**
     * @var \App\Models\Project $project
     */
@endphp


<tr>
    <td>
        <a href="{{route('project.view',['project_id'=>$project->id])}}">
            {{$project->project_name}}
        </a>
    </td>

    <td>{{\App\Helpers\Utilities::formatDate($project->start_date)}}</td>
    <td>{{\App\Helpers\Utilities::formatDate($project->end_date)}}</td>
    <td>{{$project->project_contractor?->name}}</td>
    <td>${{number_format($project->budget,2)}}</td>
    <td>{{$project->humanStatus()}}</td>
    @foreach($extra_headers as $header_key=> $header_html)
        <td data-extra_header="{{$header_key}}">
            @filter(\App\Plugins\Plugin::FILTER_PROJECT_TABLE_COLUMN,'',$header_key,$project)
        </td>
    @endforeach
    <td>
        <a href="{{route('project.view',['project_id'=>$project->id])}}" class="btn btn-outline-success">View</a>


        <a href="{{route('project.edit',['project_id'=>$project->id])}}" class="btn btn-outline-info">
            Edit
        </a>

        <form method="POST" enctype="multipart/form-data" class="will-ask-first-before-deleting-project d-inline-block"
              action="{{route('project.delete',['project_id'=>$project->id])}}">
            @csrf @method('delete')
            <button type="submit" class="btn  btn-outline-danger">
                Delete
            </button>

        </form>
    </td>
</tr>
