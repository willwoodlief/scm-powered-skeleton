@php
    /**
     * @var \App\Models\Employee $employee
     */
@endphp
<form method="POST" enctype="multipart/form-data"
      action="@if($employee->id){{route('employee.update',['employee_id'=>$employee->id])}} @else {{route('employee.create')}} @endif"
>
    @csrf
    @if($employee->id)
        @method('patch')
    @else
        @method('post')
    @endif

    <div class="row">
        <div class="mb-3 col-md-5">
            <label for="first_name" class="form-label">
                First Name <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control"
                   id="first_name" name="first_name"
                   autocomplete="off"
                   placeholder=""
                   value="{{old('first_name',$employee->first_name)}}">
            <x-input-error :messages="$errors->get('first_name')"/>
        </div>
        <div class="mb-3 col-md-5">
            <label for="last_name" class="form-label"> Last Name<span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control"
                   id="last_name" name="last_name"
                   placeholder=""
                   autocomplete="off"
                   value="{{old('last_name',$employee->last_name)}}">
            <x-input-error
                :messages="$errors->get('last_name')"/>
        </div>
        <div class="mb-3 col-md-2">
            <label for="dob" class="form-label">Date of
                Birth<span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="dob"
                   name="dob"
                   autocomplete="off"
                   value="{{old('dob',$employee->dob)}}">
            <x-input-error :messages="$errors->get('dob')"/>
        </div>
    </div>
    <div class="row">
        <div class="mb-3 col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email"
                   class="form-control" id="email" name="email"
                   placeholder="Email"
                   autocomplete="off"
                   value="{{old('email',$employee->email)}}">
        </div>
        <div class="mb-3 col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" placeholder="Password"
                   class="form-control" id="password"
                   autocomplete="off"
                   name="password">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Phone<span
                    class="text-danger">*</span></label>
            <input type="number" class="form-control" id="phone"
                   name="phone" placeholder="1234567890"
                   autocomplete="off"
                   value="{{old('phone',$employee->phone)}}">
            <x-input-error :messages="$errors->get('phone')"/>
        </div>
        <div class="col-md-6 mb-3">
            <label for="hire_date" class="form-label">Date of
                Hire<span class="text-danger">*</span></label>
            <input type="date" class="form-control"
                   id="hire_date" name="hire_date"
                   autocomplete="off"
                   value="{{old('hire_date',$employee->hire_date)}}">
            <x-input-error
                :messages="$errors->get('hire_date')"/>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <label for="searchTextField"
               class="locationn form-label">Address<span
                class="text-danger">*</span></label>
        <input id="searchTextField" type="text"
               class="form-control addformcntrl"
               autocomplete="off"
               placeholder="address" name="address"
               value="{{old('address',$employee->address)}}">
        <x-input-error :messages="$errors->get('address')"/>
    </div>
    <div class="row">
        <div class="col-sm-6 mb-3">
            <label for="city" class="form-label">City<span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control" id="city"
                   name="city" placeholder=""
                   autocomplete="off"
                   value="{{old('city',$employee->city)}}">
            <x-input-error :messages="$errors->get('city')"/>
        </div>
        <div class="col-sm-2 mb-3">
            <label for="state" class="form-label">State<span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control" id="state"
                   name="state" placeholder="wi" maxlength="2"
                   autocomplete="off"
                   value="{{old('state',$employee->state)}}">
            <x-input-error :messages="$errors->get('state')"/>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="zip" class="form-label">Zip<span
                    class="text-danger">*</span></label>
            <input type="number" class="form-control" id="zip"
                   name="zip"
                   autocomplete="off"
                   value="{{old('zip',$employee->zip)}}">
            <x-input-error :messages="$errors->get('zip')"/>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label" for="role">Role<span
                class="text-danger">*</span></label>
        <select class="default-select form-control" id="role" name="role" autocomplete="off">
            <option data-display="Select">Please select</option>

            <!-- this should really be populated by a db table and not hard coded -->
            <option value="2"
                    @if(intval(old('role',$employee->role)) === 2) selected @endif >
                Project Manager
            </option>
            <option value="3"
                    @if(intval(old('role',$employee->role) )=== 3) selected @endif >
                Foreman
            </option>
            <option value="4"
                    @if(intval(old('role',$employee->role) )=== 4) selected @endif >
                Crew Lead
            </option>
            <option value="5"
                    @if(intval(old('role',$employee->role) )=== 5) selected @endif >
                Laborer
            </option>
            <option value="6"
                    @if(intval(old('role',$employee->role) ) === 6) selected @endif >
                Office Staff
            </option>

        </select>
        <x-input-error :messages="$errors->get('role')"/>

    </div>
    <hr/>
    <div class="row">
        <h4 class="text-primary">Profile Settings</h4>
        <div class="col-md-6 mb-3">
            <label for="profile_picture" class="form-label">
                Profile Photo <span class="text-danger">*</span>
            </label>

            <div class="upload-link" title=""
                 data-toggle="tooltip" data-placement="right"
                 data-original-title="update">
                <img
                    src="{{asset($employee->get_image_relative_path())}}"
                    alt="" class="img-thumbnail" style="width: 50px">
                <input name="profile_picture" type="file"
                       accept="image/*" class="update-flie">
                <i class="fa fa-camera"></i>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="profile_bg">
                Profile Background
                <span class="text-danger">*</span>
            </label>

            <select class="default-select form-control" id="profile_bg" name="profile_bg" autocomplete="off">
                <option data-display="Select">Please select an image</option>
                @foreach(\App\Models\Employee::getBackgroundImages() as $background_name => $background_value)

                    <option value="{{$background_value}}"
                            @if($employee->profile_bg === $background_value) SELECTED @endif
                    >
                        {{$background_name}}
                    </option>

                @endforeach

            </select>
            <x-input-error
                :messages="$errors->get('profile_bg')"/>
        </div>
    </div>


    <div>
        <input type="hidden" id="lat" name="latitude"
               value="{{old('latitude',$employee->latitude??0)}}">
        <x-input-error :messages="$errors->get('latitude')"/>
        <input type="hidden" id="long" name="longitude"
               value="{{old('longitude',$employee->longitude??0)}}">
        <x-input-error :messages="$errors->get('longitude')"/>
    </div>

    <div class="card-footer">
        <input type="submit" class="btn btn-primary"
               value="Update">
    </div>
</form>
