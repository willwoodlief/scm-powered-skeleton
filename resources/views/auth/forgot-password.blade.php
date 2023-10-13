@component('layouts.guest')
    @component('components.auth-card')
        <!-- Session Status -->
        @include('includes.auth-session-status')

        <!-- Validation Errors -->
        @include('includes.auth-validation-errors')

        <form class="dz-form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <h3 class="form-title m-t0">Forget Password ?</h3>
            <div class="dz-separator-outer m-b5">
                <div class="dz-separator bg-primary style-liner"></div>
            </div>
            <p>Enter your e-mail address below to reset your password. </p>
            <div class="form-group mb-4">
                <input class="form-control" id="email" type="email" name="email" title="Email"
                       value="{{ old('email') }}" required autofocus autocomplete="email"  placeholder="Email Address"/>
            </div>
            <div class="form-group clearfix text-left">

                <a class="active btn btn-primary "
                   id="nav-personal-tab"
                   href="{{ route('login') }}"
                >
                    Login Instead
                </a>

                <button class="btn btn-primary float-end" type="submit">
                    Submit
                </button>
            </div>
        </form>

    @endcomponent
@endcomponent
