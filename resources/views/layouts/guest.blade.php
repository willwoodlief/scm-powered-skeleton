@php
 use App\Providers\ScmServiceProvider;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="keywords" content="">
        <meta name="author" content="">
        <meta name="robots" content="">

        <!-- PAGE TITLE HERE -->
        <title>{{ ScmServiceProvider::getSiteName() }} |Login</title>

        <!-- FAVICONS ICON -->
        <link rel="shortcut icon" type="image/png" href="{{asset(ScmServiceProvider::getFavicon())}}">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

        @filter(\App\Plugins\Plugin::FILTER_GUEST_EXTRA_HEAD,'')
    </head>
    <body class="vh-100">

        <div class="page-wraper">
            @if (session('error'))
                <div class="alert alert-danger mb-0 text-center">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="row gx-0">
                        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12 vh-100">
                            <div class="tab-content w-100" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-personal" role="tabpanel" aria-labelledby="nav-personal-tab">
                                    <div class="m-0 p-0">
                                        @include('layouts.guest.inserts.top-insert')
                                    </div>
                                    @php
                                        $right_sidebar = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_GUEST_SIDE_PANEL, '')
                                    @endphp
                                    <div class="row">

                                        <div class="col-12 @if(mb_strlen($right_sidebar) > 0) col-lg-10 @else  col-lg-12 @endif">
                                            <!-- Content -->
                                            {{ $slot }}
                                            <!-- Content END-->
                                        </div>


                                        @if(mb_strlen($right_sidebar) )
                                            <div class="col-12 col-lg-2">
                                                @include('layouts.guest.inserts.right-insert',['sidebar_content'=>$right_sidebar])
                                            </div>
                                        @endif
                                    </div> <!-- /row -->


                                    <div class="m-0 p-0">
                                        @include('layouts.guest.inserts.bottom-insert')
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                @filter(\App\Plugins\Plugin::FILTER_GUEST_EXTRA_FOOTER_ELEMENTS,'')
                            </div>

                        </div> <!-- /col -->
                    </div> <!-- /row -->
                </div><!-- /card body -->
            </div> <!-- /card -->
        @filter(\App\Plugins\Plugin::FILTER_GUEST_EXTRA_FOOT,'')
    </body>
</html>
