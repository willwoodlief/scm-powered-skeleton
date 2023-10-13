@php
    /**
     * @var \App\Models\Project $project
     */
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-9 wid-100">
            <div class="row">
                <div class="col-xl-3  col-lg-6 col-sm-6">
                    @include('projects.project_parts.project_status',compact('project'))
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card chart-grd same-card">
                        <div class="card-body depostit-card p-0">
                            <div class="depostit-card-media d-flex justify-content-between pb-0">
                                <div>
                                    <h6>Budget</h6>
                                    <h3><{{ number_format($project->budget, 2) }}</h3>
                                </div>
                                <div class="icon-box bg-primary-light">
                                    <svg width="12" height="20" viewBox="0 0 13 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.4642 13.7074C11.4759 12.1252 10.8504 10.8738 9.60279 9.99009C8.6392 9.30968 7.46984 8.95476 6.33882 8.6137C3.98274 7.89943 3.29927 7.52321 3.29927 6.3965C3.29927 5.14147 4.93028 4.69493 6.32655 4.69493C7.34341 4.69493 8.51331 5.01109 9.23985 5.47964L10.6802 3.24887C9.73069 2.6333 8.43112 2.21342 7.14783 2.0831V0H4.49076V2.22918C2.12884 2.74876 0.640949 4.29246 0.640949 6.3965C0.640949 7.87005 1.25327 9.03865 2.45745 9.86289C3.37331 10.4921 4.49028 10.83 5.56927 11.1572C7.88027 11.8557 8.81873 12.2813 8.80805 13.691L8.80799 13.7014C8.80799 14.8845 7.24005 15.3051 5.89676 15.3051C4.62786 15.3051 3.248 14.749 2.46582 13.9222L0.535522 15.7481C1.52607 16.7957 2.96523 17.5364 4.4907 17.8267V20.0001H7.14783V17.8735C9.7724 17.4978 11.4616 15.9177 11.4642 13.7074Z"
                                            fill="var(--primary)"/>
                                    </svg>
                                </div>
                            </div>
                            <div id="NewCustomers"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card chart-grd same-card">
                        <div class="card-body depostit-card p-0">
                            <div class="depostit-card-media d-flex justify-content-between pb-0">
                                <div>
                                    <h6>Recorded Expenses</h6>
                                    <h3>{{number_format($project->project_expenses()->count(), 2) }}</h3>
                                </div>
                                <div class="icon-box bg-danger-light">
                                    <svg width="12" height="20" viewBox="0 0 12 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.4642 13.7074C11.4759 12.1252 10.8504 10.8738 9.60279 9.99009C8.6392 9.30968 7.46984 8.95476 6.33882 8.6137C3.98274 7.89943 3.29927 7.52321 3.29927 6.3965C3.29927 5.14147 4.93028 4.69493 6.32655 4.69493C7.34341 4.69493 8.51331 5.01109 9.23985 5.47964L10.6802 3.24887C9.73069 2.6333 8.43112 2.21342 7.14783 2.0831V0H4.49076V2.22918C2.12884 2.74876 0.640949 4.29246 0.640949 6.3965C0.640949 7.87005 1.25327 9.03865 2.45745 9.86289C3.37331 10.4921 4.49028 10.83 5.56927 11.1572C7.88027 11.8557 8.81873 12.2813 8.80805 13.691L8.80799 13.7014C8.80799 14.8845 7.24005 15.3051 5.89676 15.3051C4.62786 15.3051 3.248 14.749 2.46582 13.9222L0.535522 15.7481C1.52607 16.7957 2.96523 17.5364 4.4907 17.8267V20.0001H7.14783V17.8735C9.7724 17.4978 11.4616 15.9177 11.4642 13.7074Z"
                                            fill="#FF5E5E"/>
                                    </svg>
                                </div>
                            </div>
                            <div id="NewExperience2"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card chart-grd same-card">
                        <div class="card-body depostit-card p-0">
                            <div class="depostit-card-media d-flex justify-content-between pb-0">
                                <div>
                                    <h6>Outstanding Invoices</h6>
                                    <h3>{{number_format($project->project_invoices()->sum('payment'), 2) }}</h3>
                                </div>
                                <div class="icon-box bg-warning-light">
                                    <svg width="12" height="20" viewBox="0 0 12 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.4642 13.7074C11.4759 12.1252 10.8504 10.8738 9.60279 9.99009C8.6392 9.30968 7.46984 8.95476 6.33882 8.6137C3.98274 7.89943 3.29927 7.52321 3.29927 6.3965C3.29927 5.14147 4.93028 4.69493 6.32655 4.69493C7.34341 4.69493 8.51331 5.01109 9.23985 5.47964L10.6802 3.24887C9.73069 2.6333 8.43112 2.21342 7.14783 2.0831V0H4.49076V2.22918C2.12884 2.74876 0.640949 4.29246 0.640949 6.3965C0.640949 7.87005 1.25327 9.03865 2.45745 9.86289C3.37331 10.4921 4.49028 10.83 5.56927 11.1572C7.88027 11.8557 8.81873 12.2813 8.80805 13.691L8.80799 13.7014C8.80799 14.8845 7.24005 15.3051 5.89676 15.3051C4.62786 15.3051 3.248 14.749 2.46582 13.9222L0.535522 15.7481C1.52607 16.7957 2.96523 17.5364 4.4907 17.8267V20.0001H7.14783V17.8735C9.7724 17.4978 11.4616 15.9177 11.4642 13.7074Z"
                                            fill="#FFE25E"/>
                                    </svg>
                                </div>
                            </div>
                            <div id="NewExperience"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4">
                    <div class="clearfix">
                        @include('projects.project_parts.project_profile',compact('project'))
                    </div>
                </div>


                <div class="col-xl-6">
                    @include('projects.project_parts.project_transactions',compact('project'))
                </div>

                <div class="col-xl-3 col-md-6 up-shd">
                    @include('projects.project_parts.project_chart',compact('project'))
                </div>

            </div>
        </div>

        <div class="col-xl-3 col-md-6 up-shd">
            @include('projects.project_parts.project_schedules',compact('project'))
        </div>

        <div class="col-xl-12 wid-100">
            <div class="row">
                <div class="col-xl-3 col-md-6 up-shd">
                </div>
                <div class="col-xl-5 bst-seller">
                    @include('projects.project_parts.project_documents',compact('project'))
                </div>
                <div class="col-xl-4 bst-seller">
                    @include('projects.project_parts.project_invoices',compact('project'))
                </div>

            </div>
        </div>

        <div class="col-xl-9 wid-100">
            @include('projects.project_parts.project_daily_logs',compact('project'))
        </div>

    </div>
</div>



