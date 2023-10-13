@php
    /**
     * @var \App\Models\User $user
     * @var \App\Models\Employee $employee
    * @var string $password
     */
@endphp
<h1>Welcome to the {{\App\Providers\ScmServiceProvider::getSiteName()}} portal</h1>

<p>
    Your login information is below:
    <br>
    User Name : {{$user->username}}
    <br>
    Password  : {{$password}}
    <br>
</p>

<p>
    Please Log into

    <a href="{{route('login')}}">
        {{\App\Providers\ScmServiceProvider::getSiteName()}}
    </a>
</p>
