@php
    /**
     * @var \App\Models\Contractor $contractor
     */
@endphp

<form method="POST" enctype="multipart/form-data" action="@if($contractor->id){{route('contractor.update',['contractor_id'=>$contractor->id])}} @else {{route('contractor.create')}} @endif">
    @csrf
    @if($contractor->id)
        @method('patch')
    @else
        @method('post')
    @endif


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
            @if($contractor->logo)
                <img src="{{asset($contractor->get_image_asset_path(true))}}" class="img-thumbnail mt-2" style="width: 50px" alt="logo">
            @endif

        </div>
    </div>


    <div class="row">

        <div class="col-xl-6 mb-3">
            <label for="name" class="form-label">Contractor Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{old('name',$contractor->name)}}" >
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="col-xl-6 mb-3">
            <label for="phone" class="form-label">Contractor Phone<span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="phone" name="phone" placeholder="1234567890" value="{{old('phone',$contractor->phone)}}">
            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <div class="col-xl-6 mb-3">
            <label for="searchTextField" class="locationn form-label">Address<span class="text-danger">*</span></label>
            <input id="searchTextField" type="text" class="form-control addformcntrl" placeholder="address" name="address" value="{{old('address',$contractor->address)}}">
            <x-input-error :messages="$errors->get('address')" />
        </div>

        <div class="col-xl-6 mb-3">
            <label for="city" class="form-label">City<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="city" name="city" placeholder="" value="{{old('city',$contractor->city)}}" >
            <x-input-error :messages="$errors->get('city')" />
        </div>
        <div class="col-xl-6 mb-3">
            <label for="state" class="form-label">State<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="state" name="state" placeholder="wi" maxlength="2" value="{{old('state',$contractor->state)}}">
            <x-input-error :messages="$errors->get('state')" />
        </div>
        <div class="col-xl-6 mb-3">
            <label for="zip" class="form-label">Zip<span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="zip" name="zip" value="{{old('zip',$contractor->zip)}}">
            <x-input-error :messages="$errors->get('zip')" />
        </div>

    </div>
    <div>
        <input type="submit" class="btn btn-primary me-1">
        <button class="btn btn-danger light ms-1">Cancel</button>
    </div>
</form>

