@php
    use App\Providers\ScmServiceProvider;

@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="robots" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- PAGE TITLE HERE -->
    <title>{{ ScmServiceProvider::getSiteName() }} | @yield('page_title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" type="image/png" href="{{asset(ScmServiceProvider::getFavicon())}}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


    @filter(\App\Plugins\Plugin::FILTER_FRAME_EXTRA_HEAD,'')
</head>
<body>

    @include('layouts.app.top-menu')


    <div class="content-body">
        {{--    here define the top, bottom, left and right places that plugins can fill up content with, the called main content is in the middle--}}

        <div class="m-0 p-0">
            @include('layouts.app.inserts.top-insert')
        </div>

        @php
            $right_sidebar = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_FRAME_SIDE_PANEL, '')
        @endphp

        <div class="row">

            <div class="col-12 @if(mb_strlen($right_sidebar) > 0) col-lg-10 @else  col-lg-12 @endif">
                {{ $slot }}
                @yield('main_content')
            </div>


            @if(mb_strlen($right_sidebar) )
                <div class="col-12 col-lg-2">
                    @include('layouts.app.inserts.right-insert',['sidebar_content'=>$right_sidebar])
                </div>
            @endif
        </div> <!-- /row -->


        <div class="m-0 p-0">
            @include('layouts.app.inserts.bottom-insert')
        </div>
    </div> <!-- /content-body -->



@include('layouts.app.footer')

@filter(\App\Plugins\Plugin::FILTER_FRAME_EXTRA_FOOT,'')

</body>
</html>
