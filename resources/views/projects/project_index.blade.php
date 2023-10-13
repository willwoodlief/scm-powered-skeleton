@php
    /**
     * @var \App\Models\Project[] $projects
     */
@endphp
@component('layouts.app')

    @section('page_title')
        Projects
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
                                        <h4 class="heading mb-0">Projects</h4>
                                        <div>
                                            <a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas"
                                               href="#offcanvas-new-project-form" role="button" aria-controls="offcanvas-new-project-form">
                                                + Add Project
                                            </a>

                                        </div>
                                    </div>
                                    @include('projects.project_parts.project_table',compact('projects'))
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- /container -->

        <div class="offcanvas offcanvas-end customeoff" tabindex="-1" id="offcanvas-new-project-form">
            <div class="offcanvas-header">
                <h5 class="modal-title" id="#gridSystemModal">Add Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                @include('projects.project_parts.new_project_form',['project'=>new \App\Models\Project()])
            </div>
        </div>

    @endsection
@endcomponent
