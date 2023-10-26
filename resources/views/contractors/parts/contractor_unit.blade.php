@php
    /**
     * @var \App\Models\Contractor $contractor
     */
@endphp


<div class="col-xl-3 col-lg-4 col-sm-6">
    <div class="card">
        <div class="card-body">
            <div class="card-use-box">
                <div class="crd-bx-img" style="text-align: center;display: contents">
                    <img src="{{$contractor->get_image_asset_path()}}" alt="" class="img-thumbnail" style="width: 50px; border: 2px solid rgba(62, 95, 206, 0.08)">

                </div>
                <div class="card__text">
                    <h4 class="mb-0">{{$contractor->name}}</h4>
                    <p>{{\App\Helpers\Utilities::format_phone_number($contractor->phone)}}</p>
                </div>
                <ul class="card__info">
                    <li>
                        <span class="card__info__stats">{{$contractor->countOpenProjectsByContractor()}}</span>
                        <span>In Progress</span>
                    </li>
                    <li>
                        <span class="card__info__stats">{{$contractor->countAllProjectsByContractor()}}</span>
                        <span>Projects</span>
                    </li>
                    <li>
                        <span class="card__info__stats">{{$contractor->countOpenInvoicesByContractor()}}</span>
                        <span>Open Invoices</span>
                    </li>
                </ul>
                <ul class="post-pos">
                    <li>
                        <span class="card__info__stats">Address: </span>
                        <span>{{$contractor->name}}</span>
                    </li>
                    <li>
                        <span class="card__info__stats">City, State: </span>
                        <span>
                            {{$contractor->city}},
                            {{$contractor->state}}
                            {{$contractor->zip}}
                        </span>
                    </li>
                </ul>
                <div>
                    <a href="{{route('contractor.edit',['contractor_id'=>$contractor->id])}}" class="btn btn-primary btn-sm me-2">Details</a>
                    <a href="javascript:void(0)" class="btn btn-secondary btn-sm ms-2" >Email</a>
                </div>
                @filter(\App\Plugins\Plugin::CONTRACTOR_UNIT_FOOTER,'',$contractor)

            </div> <!-- /card-use-box -->
        </div> <!-- /card-body -->
    </div> <!-- /card -->
</div><!-- /col -->
