@php
    /**
     * @var \App\Models\Project $project
     */
@endphp

<div class="card">
    <div class="card-header pb-0 border-0">
        <h4 class="heading mb-0">Expenses Breakdown</h4>
    </div>
    <div class="card-body">
        <div id="projectChart" class="project-chart0"></div>
        <div class="project-date">

            @foreach($project->chartTotalsProjectTransactions(true,false) as $trans => $trans_total)

                <div class="project-media">
                    <p class="mb-0">
                        {{$trans}}
                    </p>
                    <span>
                        {{$trans_total}}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
