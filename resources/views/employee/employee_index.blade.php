@php
    /**
     * @var \App\Models\Employee[] $employees
     */
@endphp
@component('layouts.app')

    @section('page_title')
        Employees
    @endsection

    @section('main_content')
        <div class="container">
            <!-- row -->
            <div class="page-titles">


            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive active-projects style-1">
                                    <div class="tbl-caption">
                                        <h4 class="heading mb-0">Employees</h4>
                                        <div>
                                            <a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas"
                                               href="#offcanvas-create-employee-form" role="button" aria-controls="offcanvas-create-employee-form"
                                            >
                                                + Add Employee
                                            </a>
                                        </div>
                                    </div>
                                    @include('employee.parts.employee_table',['employees'=>$employees])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="offcanvas offcanvas-end customeoff" tabindex="-1" id="offcanvas-create-employee-form">
            <div class="offcanvas-header">
                <h5 class="modal-title" id="#gridSystemModal">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                @include('employee.parts.add_change_employee',['employee'=>new \App\Models\Employee()])
            </div>
        </div>


    @endsection

@endcomponent


