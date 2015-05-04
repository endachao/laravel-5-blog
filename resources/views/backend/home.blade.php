@extends('app')

@section('content')
<div class="container-fluid">


	<div class="row">

        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading">导航</div>


                    <div class="panel-body text-center">

                        <ul class="nav nav-list">
                            <li>
                                <a href="{{ URL::route('backend.user.index')}}">管理员列表</a>
                            </li>
                        </ul>

                    </div>


            </div>
        </div>

		<div class="col-md-10">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
					You are logged in!
				</div>
			</div>
		</div>

	</div>
</div>
@endsection
