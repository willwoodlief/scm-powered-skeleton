@component('layouts.app')

    @section('page_title')
        Edit Profile
    @endsection

    @section('main_content')

        <div class="container">
            <h1>
                {{ __('Profile') }}
            </h1>

            @include('profile.partials.update-profile-information-form')

            @include('profile.partials.update-password-form')

            @include('profile.partials.delete-user-form')
        </div> <!-- /container -->

    @endsection
@endcomponent
