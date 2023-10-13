@component('layouts.app')

    @section('page_title')
        Dashboard
    @endsection

    @section('main_content')
        <div class="container ">
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-9 ">
                        <div class="row">
                            <div class="col-4">
                                <div class="card chart-grd same-card">
                                    <div class="card-body p-0">
                                        <div class="d-flex justify-content-between pb-0">
                                            <div>
                                                <h6>Total Sales YTD</h6>
                                                <h3>{{number_format(\App\Models\Project::salesYTD())}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card ">
                                    <div class="card-body  p-0">
                                        <div class=" d-flex justify-content-between pb-0">
                                            <div>
                                                <h6>Recorded Expenses</h6>
                                                <h3>{{number_format(\App\Models\ConstructionExpense::totalExpenses())}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="card ">
                                    <div class="card-body  p-0">
                                        <div class=" d-flex justify-content-between pb-0">
                                            <div>
                                                <h6>Outstanding Invoices</h6>
                                                <h3>{{number_format(\App\Models\Invoice::totalBalance())}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- /row -->

                    <div class="row mt-4">
                        <div class="col-12 col-xl-6">
                            <div class="card">
                                @include('projects.project_parts.active-projects',compact('active_projects'))
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 up-shd">
                            <div class="card">
                                <div class="card-body  p-2">
                                    <div class="events">
                                        <h6>events this month</h6>
                                        <div class="dz-scroll event-scroll">

                                            @include("employee.parts.employee_birthdays") <br />
                                            @include("projects.project_parts.projects_starting_this_month")
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card" >
                                <div class="card-header border-0">
                                    <h4 class="heading mb-0">My To Do Items</h4>
                                </div>
                                <div class="card-body p-0">
                                    @include('tasks.todo_list')
                                </div>
                            </div>
                        </div>

                    </div>


                </div>

                <div class="row mt-3">
                    <div class="col-12r">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive active-projects style-1">
                                    <div class="tbl-caption">
                                        <h4 class="heading mb-0">Employees</h4>

                                    </div>
                                    @include('employee.parts.employee_table',compact('employees'))
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    @endsection

@endcomponent
