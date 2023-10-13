@php
    use App\Models\Project;
@endphp
@php
    /**
     * @var Project $project
     */
@endphp

<form method="POST" enctype="multipart/form-data" action="{{route('project.update',['project_id'=>$project->id])}}">
    @csrf
    @method('patch')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="clearfix">
                <div class="card card-bx profile-card author-profile m-b30">
                    <div class="card-body">
                        <div class="p-5">
                            <div class="author-profile">
                                <div class="author-media">
                                    <a href="#">
                                        <img src="{{asset($project->project_contractor->get_image_asset_path(true))}}" alt="" class="img-thumbnail" style="width: 30px;border-radius: 0">
                                    </a>
                                </div>

                                <div class="author-info">
                                    <h6 class="title">{{$project->project_name}}</h6>
                                    <span>
                                        @php
                                            $address_line = $project['address'] . " " . $project['city'] . ", " . $project['state'] . " " . $project['zip'];
                                            $address = urlencode($address_line);
                                            $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . $address;
                                        @endphp
                                        <a href="{{$mapsUrl}}" target="_blank">
                                            {{$address_line}}
                                        </a>

                                    </span>
                                </div>
                            </div>
                            <div class="info-list">
                                <ul>
                                    <li>
                                        <div class="input-group input-group-sm mb-3">
                                            <span class="input-group-text">PM Name</span>
                                            <input type="text" name="pm_name" id="pm_name" class="form-control"
                                                   title="Project Manager Name"
                                                   value="{{old('pm_name',$project->pm_name)}}">
                                            <x-input-error :messages="$errors->get('pm_name')" />

                                            <span class="input-group-text">PM Phone</span>
                                            <input type="text" name="pm_phone" id="pm_phone" class="form-control"
                                                   title="Project Manager Phone"
                                                   value="{{old('pm_phone',$project->pm_phone)}}">
                                            <x-input-error :messages="$errors->get('pm_phone')" />

                                        </div>
                                    </li>
                                    <li>
                                        <div class="input-group input-group-sm mb-3">
                                            <span class="input-group-text">Super Name</span>
                                            <input type="text" name="super_name" id="super_name" class="form-control"
                                                   title="Super name"
                                                   value="{{old('super_name',$project->super_name)}}">
                                            <x-input-error :messages="$errors->get('super_name')" />

                                            <span class="input-group-text">Super Phone</span>
                                            <input type="text" name="super_phone" id="super_phone" class="form-control"
                                                   title="Super phone"
                                                   value="{{old('super_phone',$project->super_phone)}}">
                                            <x-input-error :messages="$errors->get('super_phone')" />
                                        </div>
                                    </li>
                                    <li>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text mb-0">Status</label>
                                            <div class="dropdown bootstrap-select default-select form-control wide">
                                                <select class="default-select form-control wide" name="status"
                                                        title="Status">
                                                    <option value="0" @if(intval(old('pm',$project->status)) === Project::STATUS_NOT_STARTED) selected @endif >
                                                        Not Started
                                                    </option>
                                                    <option value="1" @if(intval(old('pm',$project->status)) === Project::STATUS_IN_PROGRESS) selected @endif >
                                                        In Progress
                                                    </option>
                                                    <option value="2" @if(intval(old('pm',$project->status)) === Project::STATUS_COMPLETED) selected @endif >
                                                        Completed
                                                    </option>
                                                </select>
                                                <x-input-error :messages="$errors->get('status')" />
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="info-list"></div>
                        </div>
                    </div> <!-- /card-body -->

                </div><!-- /card-->
            </div>  <!-- /clearFix-->
        </div> <!-- /col-->

        <div class="col-xl-8 col-lg-6">
            <div class="card profile-card card-bx m-b30">
                <div class="card-header">
                    <h6 class="title">Project Details</h6>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 m-b30">
                            <label class="form-label" for="project_name">Project Name</label>
                            <input type="text" name="project_name" id="project_name"  class="form-control" placeholder="" value="{{old('project_name',$project->project_name)}}">
                            <x-input-error :messages="$errors->get('project_name')" />
                        </div>

                        <div class="col-sm-6 m-b30">
                            <label class="form-label" for="address">Address</label>
                            <input id="address" type="text" class="form-control addformcntrl" placeholder="address" name="address"  value="{{old('address',$project->address)}}">
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

                            <select name="foreman[]" id="project-dropdown-4" class="js-example-basic-multiple  form-control h-auto" multiple="multiple" >
                                @php
                                    $saved_foremen = $project->getForemen();
                                    $saved_formen_hash = [];
                                    foreach ($saved_foremen as $a_fore) {
                                        $saved_formen_hash['f-'.$a_fore->id] = $a_fore;
                                    }
                                @endphp
                                @foreach ($foremen as $foreman)
                                    @php $saved_foreman = ($saved_formen_hash['f-'.$foreman->id]??null) @endphp
                                    <option value="{{$foreman->id}}" @if($saved_foreman) selected @endif>
                                        {{$foreman->getName()}}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('foreman')" />
                        </div>

                    </div> <!-- /row -->
                </div> <!-- /card-body -->
                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" value="Update">
                </div>

            </div><!-- /card -->
        </div> <!-- /col -->
    </div> <!-- /row -->
</form> <!-- /container-fluid -->
