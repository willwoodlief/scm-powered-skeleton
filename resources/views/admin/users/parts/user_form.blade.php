@php
    /**
     * @var \App\Models\User $some_user
     */
    if ($some_user->id) {
        $fid = (string)$some_user->id;
    } else {
        $fid = '0';
    }
    $fid = '-'.$fid;
@endphp

<form method="post"  enctype="multipart/form-data"
      action="@if($some_user->id){{route('admin.user.update',['user_id'=>$some_user->id])}} @else {{route('admin.user.create')}} @endif"
>
    @csrf
        @if($some_user->id)
            @method('patch')
            <h2>
                @lang('Update User')
                {{$some_user->getName()}}
            </h2>
        @else
            @method('post')
        <h2>
            @lang(__('Create User'))
        </h2>
        @endif
    <div>
        <x-input-label for="username{{$fid}}" :value="__('Username')" />
        <x-text-input id="username{{$fid}}" name="username" type="text" :value="old('username', $some_user->username)"
                      required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('username')" />
    </div>

    <div>
        <x-input-label for="fname{{$fid}}" :value="__('First Name')" />
        <x-text-input id="fname{{$fid}}" name="fname" type="text" :value="old('fname', $some_user->fname)"
                      required autofocus autocomplete="fname" />
        <x-input-error :messages="$errors->get('fname')" />
    </div>

    <div>
        <x-input-label for="lname{{$fid}}" :value="__('Last Name')" />
        <x-text-input id="lname{{$fid}}" name="lname" type="text" :value="old('lname', $some_user->lname)"
                      required autofocus autocomplete="lname" />
        <x-input-error :messages="$errors->get('lname')" />
    </div>

    <div>
        <x-input-label for="title{{$fid}}" :value="__('Title ')" />
        <x-text-input id="title{{$fid}}" name="title" type="text" :value="old('title', $some_user->title)"
                      required autofocus autocomplete="title" />
        <x-input-error :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="email{{$fid}}" :value="__('Email')" />
        <x-text-input id="email{{$fid}}" name="email" type="email" :value="old('email', $some_user->email)"
                      required autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" />

        @if ($some_user->id && $some_user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $some_user->hasVerifiedEmail())
            <div>
                <p>
                    {{ __('Email address is unverified.') }}
                </p>
            </div>
        @endif
    </div>

    <div>
        <x-input-label for="password{{$fid}}" :value="__('Password')" />
        <x-text-input id="password{{$fid}}" name="password" type="password" autocomplete="new-password" value="{{old('password', '')}}" maxlength="50"/>
        <x-input-error :messages="$errors->updatePassword->get('password')" />
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
            @if($some_user->profile)
                <img src="{{asset($some_user->get_image_relative_path(true))}}" class="img-thumbnail mt-3" style="width: 50px"  alt="profile">
            @endif

        </div>
    </div>

    <div>
        <label for="user_roles{{$fid}}">
            {{__('User Roles')}}
        </label>

        <select class="form-control" multiple
                name="user_roles[]" id="user_roles{{$fid}}" style="height: {{round(count(\App\Models\UserRole::ALL_ROLES)*1.75)}}em;"
        >
            @foreach(\App\Models\UserRole::ALL_ROLES as $role_value => $role_name)
                <option
                    value="{{$role_value}}"
                    @if($some_user->has_role($role_value)) SELECTED @endif
                >
                    {{$role_name}}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('title')" />
    </div>

    <div>
        <button type="submit" class="btn btn-primary">
            @if($some_user->id)
                {{ __('Update') }}
            @else
                {{ __('Create') }}
            @endif

        </button>
    </div>
</form>


