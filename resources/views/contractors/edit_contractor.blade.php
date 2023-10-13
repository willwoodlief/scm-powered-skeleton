@php
    /**
     * @var \App\Models\Contractor $contractor
     */
@endphp

@component('layouts.app')

    @section('page_title')
        Edit Contractor
    @endsection

    @section('main_content')
        <div class="container">
            <!-- row -->

            <div class="container-fluid">
                <div class="col-12 col-lg-12 col-xl-6 col-xxl-4">
                    <div class="card w-100">
                        <div class="card-body card w-100">
                            @include('contractors.parts.contractor_form',compact('contractor'))
                        </div>
                    </div>
                </div>

            </div> <!-- /container -->
        </div> <!-- /container -->

    @endsection
@endcomponent
