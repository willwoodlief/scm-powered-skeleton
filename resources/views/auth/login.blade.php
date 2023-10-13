@php
    use App\Providers\ScmServiceProvider;
@endphp
@component('layouts.guest')
    @component('components.auth-card')

<form method="POST" class=" dz-form pb-3" action="{{ route('login') }}" >
    @csrf
    <h3 class="form-title m-t0">
       {{\App\Providers\ScmServiceProvider::getSiteName()}}
        Portal
    </h3>
    <div class="dz-separator-outer m-b5">
        <div class="dz-separator bg-primary style-liner"></div>
    </div>

    <p>Enter your username and your password.
        <!-- Validation Errors -->
        @include('includes.auth-validation-errors')
    </p>

    <div class="form-group mb-3">
        <input class="form-control" id="username" type="text" name="username"  placeholder="Username" title="User Name"
               value="{{ old('username') }}" required autofocus autocomplete="username" >
    </div>
    <div class="form-group mb-3">
        <input id="password" type="password" name="password" required autocomplete="current-password" title="Password" class="form-control" />
    </div>
    <div class="form-group text-left mb-5 forget-main">
        <button type="submit" class="btn btn-primary">Sign Me In</button>

        <span class="form-check d-inline-block" style="float:right;padding: 7px 5px 0 0">
            <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
            <label class="form-check-label" for="remember_me">Remember me</label>
        </span>
        <a class="nav-link m-auto btn tp-btn-light btn-primary forget-tab "
           id="nav-forget-tab"
           href="{{ route('password.request') }}"
        >
            Forget Password ?
        </a>
    </div>

</form>
<!-- Session Status -->
@include('includes.auth-session-status')









    @endcomponent
@endcomponent
