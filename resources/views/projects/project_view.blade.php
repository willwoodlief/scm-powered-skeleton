@php
    /**
     * @var \App\Models\Project $project
     */
@endphp
@component('layouts.app')

    @section('page_title')
        View Project
    @endsection

    @section('main_content')
        <div class="container">
            <!-- row -->


            @include('projects.project_parts.project_view_inner',compact('project'))

        </div>
    @endsection
@endcomponent
