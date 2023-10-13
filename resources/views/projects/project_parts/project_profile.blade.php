@php
    /**
     * @var \App\Models\Project $project
     */
@endphp

<div class="card card-bx profile-card author-profile m-b30">
    <div class="card-body">
        <div class="p-5">
            <div class="author-profile">

                <div class="author-media">
                    <a href="#">
                        <img src="{{$project->project_contractor->get_image_asset_path()}}" alt="" class="img-thumbnail mt-2" style="width: 50px;border-radius: 0" >
                    </a>
                </div>
                <div class="author-info">
                    <h6 class="title">{{$project->project_name}}</h6>
                    <span>
                        @php
                            $address = urlencode($project->address . " " . $project->city . ", " . $project->state . " " . $project->zip);
                            $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . $address;
                        @endphp
                        <a href="<?php echo $mapsUrl; ?>" target="_blank">
                            {{ $project->address . " " . $project->city . ", " . $project->state . " " . $project->zip }}
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <div class="info-list">
            <ul>
                <li>
                    PM:
                    <span>
                        {{$project->pm_name? $project->pm_name : $project->project_manager?->first_name . ' '. $project->project_manager?->last_name}}
                        <br/>
                        {{ \App\Helpers\Utilities::format_phone_number($project->pm_phone) }}
                    </span>
                <li>
                    <a href="#">Super:</a>
                    <span>
                        {{$project->super_name}}
                        <br/>
                        {{ \App\Helpers\Utilities::format_phone_number($project->super_phone) }}
                    </span>
                </li>
                <li>
                    Status:
                    <div>
                            @if($project->status === \App\Models\Project::STATUS_NOT_STARTED)
                            <form method="POST" enctype="multipart/form-data"
                                  action="{{route('project.update',['project_id'=>$project->id])}}">
                                @csrf @method('patch')
                                <input type="hidden" name="status"
                                       value="{{\App\Models\Project::STATUS_IN_PROGRESS}}">
                                <button type="submit"
                                        class="btn btn-rounded btn-outline-warning">
                                    Start Project?
                                </button>
                            </form>

                        @elseif($project->status === \App\Models\Project::STATUS_IN_PROGRESS)

                            <form method="POST" enctype="multipart/form-data"
                                  action="{{route('project.update',['project_id'=>$project->id])}}"
                            >
                                @csrf @method('patch')
                                <input type="hidden" name="status"
                                       value="{{\App\Models\Project::STATUS_COMPLETED}}">
                                <button type="submit"
                                        class="btn btn-rounded btn-outline-success">
                                    Finish Project?
                                </button>
                            </form>

                        @elseif($project->status === \App\Models\Project::STATUS_COMPLETED)
                            <button type="button" class="btn btn-rounded btn-outline-dark" disabled>Archive Project?</button>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-footer">
        <div class="info-list">
            <a href="{{route('project.edit',['project_id'=>$project->id])}}">
                <button type="button" class="btn btn-outline-info">Edit Project</button>
            </a>
        </div>
    </div>
</div>
