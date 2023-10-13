@php
    /**
     * @var \App\Models\Employee $employee
     */
@endphp
@component('layouts.app')

    @section('page_title')
        Employee Profile
    @endsection

    @section('main_content')
        <div class="container">

            <div class="container-fluid">
                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="profile card card-body px-3 pt-3 pb-0">
                            <div class="profile-head">
                                <div class="photo-content">
                                    <div class="cover-photo rounded"
                                         style="background:url({{asset('app-assets/images/background')}}/{{old('profile_bg',$employee->profile_bg)}});background-size: cover;background-position: center"></div>
                                </div>
                                <div class="profile-info">
                                    <div class="profile-photo" id="lightgallery">
                                        <a href="{{asset($employee->get_image_relative_path())}}"
                                           data-exthumbimage="{{$employee->get_image_asset_path(true)}}"
                                           data-src="{{asset($employee->get_image_relative_path())}}"
                                        ><img src="{{asset($employee->get_image_relative_path())}}"
                                              class="img-fluid rounded-circle-profile" alt=""></a>
                                    </div>
                                    <div class="profile-details">
                                        <div class="profile-name px-3 pt-2">
                                            <h4 class="text-primary mb-0">{{$employee->first_name}} {{$employee->last_name}}</h4>
                                            <p>{{$employee->humanRole()}}</p>
                                        </div>
                                        <div class="profile-email px-2 pt-2">
                                            <h4 class="text-muted mb-0">{{$employee->email}}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-xl-8">
                        <div class="card h-auto">
                            <div class="card-body">
                                <div class="profile-tab">
                                    <div class="custom-tab-1">
                                        <ul class="nav nav-tabs">

                                            <li class="nav-item">
                                                <a href="#profile-settings" data-bs-toggle="tab" class="nav-link active show">Setting</a>
                                            </li>
                                            @if(Auth::user()->has_role(\App\Models\UserRole::USER_ROLE_HR))
                                                <li class="nav-item">
                                                    <a href="#hr" data-bs-toggle="tab" class="nav-link">
                                                        HR (Admin)
                                                    </a>
                                                </li>
                                            @endif  {{-- /if hr role --}}
                                        </ul>
                                        <div class="tab-content">


                                            <div id="profile-settings" class="tab-pane fade active show">
                                                <div class="pt-3">
                                                    <div class="settings-form">
                                                        <h4 class="text-primary">Account Settings</h4>
                                                       @include('employee.parts.employee_form',['employee'=>$employee])
                                                    </div>
                                                </div>
                                            </div>

                                            @if(Auth::user()->has_role(\App\Models\UserRole::USER_ROLE_HR))
                                                <div id="hr" class="tab-pane fade">
                                                    @include('employee.hr.employee_hr',['employee'=>$employee])
                                                </div>
                                            @endif {{-- /if hr role --}}
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection
@endcomponent
