@php
    /**
     * @var \App\Models\User $user
     */
@endphp
<section>
    <header>
        <h2>
            {{ __('Profile Information') }}
        </h2>

        <p>
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" :value="old('username', $user->username)"
                          required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="fname" :value="__('First Name')" />
            <x-text-input id="fname" name="fname" type="text" :value="old('fname', $user->fname)"
                          required autofocus autocomplete="fname" />
            <x-input-error :messages="$errors->get('fname')" />
        </div>

        <div>
            <x-input-label for="lname" :value="__('Last Name')" />
            <x-text-input id="lname" name="lname" type="text" :value="old('lname', $user->lname)"
                          required autofocus autocomplete="lname" />
            <x-input-error :messages="$errors->get('lname')" />
        </div>

        <div>
            <x-input-label for="title" :value="__('Title ')" />
            <x-text-input id="title" name="title" type="text" :value="old('title', $user->title)"
                          required autofocus autocomplete="title" />
            <x-input-error :messages="$errors->get('title')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)"
                          required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p>
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p>
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label>Profile Picture</label>
            <div class="dz-default dlab-message upload-img mb-3">

                <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M27.1666 26.6667L20.4999 20L13.8333 26.6667" stroke="#DADADA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M20.5 20V35" stroke="#DADADA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M34.4833 30.6501C36.1088 29.7638 37.393 28.3615 38.1331 26.6644C38.8731 24.9673 39.027 23.0721 38.5703 21.2779C38.1136 19.4836 37.0724 17.8926 35.6111 16.7558C34.1497 15.619 32.3514 15.0013 30.4999 15.0001H28.3999C27.8955 13.0488 26.9552 11.2373 25.6498 9.70171C24.3445 8.16614 22.708 6.94647 20.8634 6.1344C19.0189 5.32233 17.0142 4.93899 15.0001 5.01319C12.9861 5.0874 11.015 5.61722 9.23523 6.56283C7.45541 7.50844 5.91312 8.84523 4.7243 10.4727C3.53549 12.1002 2.73108 13.9759 2.37157 15.959C2.01205 17.9421 2.10678 19.9809 2.64862 21.9222C3.19047 23.8634 4.16534 25.6565 5.49994 27.1667" stroke="#DADADA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M27.1666 26.6667L20.4999 20L13.8333 26.6667" stroke="#DADADA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="fallback">
                    <input name="profile_picture" type="file" accept="image/*">
                    <x-input-error :messages="$errors->get('profile_picture')" />
                </div>
                @if($user->profile)
                    <img src="{{asset($user->get_image_relative_path(true))}}" class="img-thumbnail" style="width: 50px" alt="profile">
                @endif

            </div>
        </div>

        <div>
            <button>{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
