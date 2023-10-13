@php
    use App\Providers\ScmServiceProvider;
    use App\Providers\RouteServiceProvider;
    use App\Helpers\Utilities;
@endphp
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a href="{{route('dashboard')}}" class="navbar-brand">
            <img src="{{ScmServiceProvider::getLogo()}}" style="height: 40px" alt="Logo"/>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{route(RouteServiceProvider::DASHBOARD)}}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route(RouteServiceProvider::EMPLOYEES)}}">Employees</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route(RouteServiceProvider::PROJECTS) }}">Projects</a>
                </li>

                @if(Auth::user()->has_role(\App\Models\UserRole::USER_ROLE_ACCOUNTING))
                    <li class="nav-item">
                        <a class="nav-link" href="{{route(RouteServiceProvider::TRANSACTIONS)}}">Transactions</a>
                    </li>
                @endif {{-- /if accounting role --}}

                <li class="nav-item">
                    <a class="nav-link" href="{{route(RouteServiceProvider::CONTRACTORS)}}">Contractors</a>
                </li>

                @if(Auth::user()->has_role(\App\Models\UserRole::USER_ROLE_ACCOUNTING))
                    <li class="nav-item">
                        <a class="nav-link" href="{{route(RouteServiceProvider::ACCOUNTING)}}">Accounting</a>
                    </li>
                @endif {{-- /if accounting role --}}

                <li class="nav-item">
                    <a class="nav-link" href="{{route(RouteServiceProvider::NOTIFICATIONS) }}">Notifications</a>
                </li>

                @if(\App\Helpers\Utilities::get_logged_user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin')}}">Admin</a>
                    </li>
                @endif  {{-- /if admin role --}}

                <li class="nav-item">
                    <a class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" href="#offcanvasExample1" role="button" aria-controls="offcanvasExample1">
                        + Add Task
                    </a>
                </li>

                @filter(\App\Plugins\Plugin::FILTER_FRAME_END_TOP_MENU,'')

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <div class="d-inline-block">
                            <h6>
                                <img src="{{asset(Utilities::get_logged_user()->get_image_relative_path(true) )}}" alt="logo" class="img-thumbnail" style="width: 30px">
                                {{Utilities::get_logged_user()->getName()}}
                            </h6>

                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{route('profile.edit')}}">Profile</a>
                        </li>
                        @filter(\App\Plugins\Plugin::FILTER_FRAME_END_USER_MENU,'')

                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline-block m-0">
                                @csrf

                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                   class="dropdown-item"
                                >
                                    Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

