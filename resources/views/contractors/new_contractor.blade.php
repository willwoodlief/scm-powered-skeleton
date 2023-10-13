@component('layouts.app')

    @section('page_title')
        Add Contractor
    @endsection

    @section('main_content')
        <div class="container">
            <!-- row -->

            <div class="container-fluid">
                @include('contractors.parts.contractor_form')
            </div> <!-- /container -->
        </div> <!-- /container -->

    @endsection
@endcomponent
