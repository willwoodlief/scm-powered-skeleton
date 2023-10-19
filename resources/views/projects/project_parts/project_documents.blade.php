@php
    /**
     * @var \App\Models\Project $project
     */
@endphp

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive active-projects style-1">
            <div class="tbl-caption">
                <h4 class="heading mb-0">Documents</h4>
                <div>
                    <!--suppress HtmlUnknownAnchorTarget -->
                    <a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas"
                       href="#add-project-file-dialog"
                       role="button" aria-controls="add-project-file-dialog"
                    >
                        + Add File
                    </a>
                </div>
            </div>

            @php
                $extra_headers = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_PROJECT_DOC_TABLE_HEADERS, [])
            @endphp
            <table id="projects-tbl" class="table">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>File</th>
                    <th>Date</th>
                    <th>Size</th>
                    @foreach($extra_headers as $header_key=> $header_html)
                        <th data-extra_header="{{$header_key}}">{{$header_html}}</th>
                    @endforeach
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                @forelse($project->get_project_files() as $project_file)

                    <tr>
                        <td>
                            <img
                                src='{{asset("app-assets/icon/fontawesome6/svgs/solid/".$project_file->getFileIcon())}}'
                                alt='{{$project_file->getFileExtension()}}' width='20px'/>
                        </td>
                        <td>
                            <a href="{{$project_file->getPublicFilePath()}}">
                                {{$project_file->getFileNameWithExtention()}}
                            </a>
                        </td>
                        <td>{{$project_file->getFileDate()}}</td>
                        <td>{{$project_file->getFileSize()}}Mb</td>
                        @foreach($extra_headers as $header_key=> $header_html)
                            <td data-extra_header="{{$header_key}}">
                                @filter(\App\Plugins\Plugin::FILTER_PROJECT_DOC_TABLE_COLUMN,'',$header_key,$project_file)
                            </td>
                        @endforeach
                        <td>
                            <div style="font-size: 20px">
                                <a href="{{$project_file->getPublicFilePath()}}" @if($project_file->isDownloadableFromThisServer()) download @endif>
                                    <i class="las la-file-download"></i>
                                </a>
                                <form method="POST" enctype="multipart/form-data"
                                      class="will-ask-first-before-deleting-file d-inline-block"
                                      action="{{route('project.file.delete',['project_id'=>$project->id])}}">
                                    @csrf @method('delete')
                                    <input type="hidden" name="project_file_name"
                                           value="{{$project_file->getFileNameWithExtention()}}">
                                    <button type="submit" class="btn btn-rounded btn-outline-danger border-0">
                                        <i class="las la-times-circle"></i>
                                    </button>

                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td></td>
                        <td>No documents found for this project.</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforelse

                </tbody>

            </table>
            <script>
                jQuery(function ($) {
                    $(`form.will-ask-first-before-deleting-file`).on('submit', function () {
                        return confirm('Are you sure you want to delete this file?');
                    });
                });
            </script>


        </div>
    </div>
</div>

@include('projects.project_parts.add_project_file',compact('project'))

