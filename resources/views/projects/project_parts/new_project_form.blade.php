@php
    /**
     * @var \App\Models\Project $project
     * @var \App\Models\Contractor[] $contractors
     * @var \App\Models\Employee[] $project_managers
     * @var \App\Models\Employee[] $foremen
     */
@endphp


<div class="container-fluid">
    <form method="POST" enctype="multipart/form-data" action="{{route('project.create')}}">
        @csrf
        <div class="row">
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="project_name">Project Name</label>
                <input type="text" name="project_name" id="project_name"  class="form-control" placeholder="" value="{{old('project_name',$project->project_name)}}">
                <x-input-error :messages="$errors->get('project_name')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="searchTextField">Address</label>
                <input id="searchTextField" type="text" class="form-control addformcntrl" placeholder="address" name="address"  value="{{old('address',$project->address)}}">
                <x-input-error :messages="$errors->get('address')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="city">City</label>
                <input type="text" name="city" id="city" class="form-control"  placeholder="" value="{{old('city',$project->city)}}">
                <x-input-error :messages="$errors->get('city')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="state">State</label>
                <input type="text" name="state" id="state" class="form-control"  maxlength="2" placeholder="" value="{{old('state',$project->state)}}">
                <x-input-error :messages="$errors->get('state')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="zip">Zip</label>
                <input type="number" name="zip" id="zip" class="form-control" placeholder="" value="{{old('zip',$project->zip)}}">
                <x-input-error :messages="$errors->get('zip')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="budget">Budget</label>
                <input type="number" name="budget" id="budget" class="form-control" placeholder="" value="{{old('budget',$project->budget)}}">
                <x-input-error :messages="$errors->get('budget')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" placeholder="" value="{{old('start_date',$project->start_date)}}">
                <x-input-error :messages="$errors->get('start_date')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" placeholder="" value="{{old('end_date',$project->end_date)}}">
                <x-input-error :messages="$errors->get('end_date')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="pm_name">Project Manager Name</label>
                <input type="text" name="pm_name"  id="pm_name" class="form-control" placeholder="" value="{{old('pm_name',$project->pm_name)}}">
                <x-input-error :messages="$errors->get('pm_name')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="pm_phone">Project Manager Phone</label>
                <input type="text" name="pm_phone" id="pm_phone" class="form-control" placeholder="" value="{{old('pm_phone',$project->pm_phone)}}">
                <x-input-error :messages="$errors->get('pm_phone')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="super_name">Super Name</label>
                <input type="text" name="super_name" id="super_name" class="form-control" placeholder="" value="{{old('super_name',$project->super_name)}}">
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="super_phone">Super Phone</label>
                <input type="text" name="super_phone" id="super_phone"   class="form-control" placeholder="" value="{{old('super_phone',$project->super_phone)}}">
                <x-input-error :messages="$errors->get('super_phone')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="project-dropdown-1">General Contractor</label>
                <select name="contractor" id="project-dropdown-1" class="form-control">

                    @foreach ($contractors as $contractor)
                        <option value="{{$contractor->id}}" @if(intval(old('contractor',$project->contractor)) === $contractor->id) selected @endif>
                                {{$contractor->getName()}}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('contractor')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="project-dropdown-2">Project Manager</label>
                <select name="pm" id="project-dropdown-2" class="form-control">

                    @foreach ($project_managers as $pm)
                        <option value="{{$pm->id}}" @if(intval(old('pm',$project->pm)) === $pm->id) selected @endif>
                            {{$pm->getName()}}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('pm')" />
            </div>
            <div class="col-sm-6 m-b30">
                <label class="form-label" for="project-dropdown-4">Foreman</label>

                <select name="foreman[]" id="project-dropdown-4" class="js-example-basic-multiple  form-control h-auto" multiple="multiple">

                    @foreach ($foremen as $foreman)
                        <option value="{{$foreman->id}}" >
                            {{$foreman->getName()}}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('foreman')" />
            </div>
            <div>
                <input type="hidden" class="form-control" id="lat" name="latitude" value="{{old('latitude',$project->latitude)}}">
                <x-input-error :messages="$errors->get('latitude')" />
                <input type="hidden" class="form-control" id="long" name="longitude" value="{{old('longitude',$project->longitude)}}">
                <x-input-error :messages="$errors->get('longitude')" />
            </div>
        </div> <!-- /row -->

        <div class="card-footer">
            <input type="submit" class="btn btn-primary" value="Create">
        </div>
    </form>
</div>
