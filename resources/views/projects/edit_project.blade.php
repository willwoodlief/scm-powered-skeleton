@php
    /**
     * @var \App\Models\Project $project
     */
@endphp
@component('layouts.app')

    @section('page_title')
        Edit Project
    @endsection

    @section('main_content')
        <div class="container">
            <!-- row -->

            <div class="container-fluid">
                @include('projects.project_parts.edit_project_form',compact('project'))
            </div>
        </div>

    @endsection
@endcomponent
