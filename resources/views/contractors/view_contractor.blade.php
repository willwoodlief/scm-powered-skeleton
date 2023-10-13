@php
    /**
    * @var \App\Models\Contractor $contractor
    */
@endphp

@section('page_title')
    Contractor View
@endsection

@section('main_content')
    <h1>
        Contractor view stub
    </h1>
    <div class="row">
        @include('contractors.parts.contractor_unit',compact('contractor'))
    </div>
@endsection

