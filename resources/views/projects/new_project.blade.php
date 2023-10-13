@php
    /**
     * @var \App\Models\Project $project
     */
@endphp
@component('layouts.app')

    @section('page_title')
        New Project
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
                                        <h4 class="heading mb-0">New Project</h4>
                                    </div>
                                    @include('projects.project_parts.new_project_form',compact('project'))
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- /container -->

    @endsection
@endcomponent
