@component('layouts.app')

    @section('page_title')
        Admin
    @endsection

    @section('main_content')
        <div class="container">
            <!-- row -->

            <div class="container-fluid">
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="{{route('admin.users')}}">
                            Users
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{route('admin.logs')}}">
                            Logs
                        </a>
                    </li>
                    @filter(\App\Plugins\Plugin::FILTER_FRAME_ADMIN_LINKS,'')
                </ul>
            </div> <!-- container-fluid -->
        </div> <!-- container -->

    @endsection
@endcomponent
