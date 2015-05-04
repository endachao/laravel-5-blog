@extends('backend.app')

@section('modules')
<div class="container-fluid">


    <div class="row">

        <div class="col-md-2">
            @include('backend.content._menu')
        </div>

        @yield('content')

    </div>
</div>
@endsection
