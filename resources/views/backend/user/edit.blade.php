@extends('backend.content.common')

@section('content')
<div class="col-md-10">
    <div class="panel panel-default">
        <div class="panel-heading">修改分类</div>

        @if ($errors->has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <strong>Error!</strong>
            {{ $errors->first('error', ':message') }}
            <br />
            请联系开发者！
        </div>
        @endif

        <div class="panel-body">
            {!! Form::model($user, ['route' => ['backend.user.update', $user->id], 'method' => 'put','class'=>'form-horizontal']) !!}

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">用户名</label>
                <div class="col-sm-3">
                    {!! Form::text('name', $user->name, ['class' => 'form-control','placeholder'=>'Username']) !!}
                    <font color="red">{{ $errors->first('name') }}</font>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">邮箱</label>
                <div class="col-sm-3">
                    {!! Form::text('email', $user->email, ['class' => 'form-control','placeholder'=>'Email']) !!}
                    <font color="red">{{ $errors->first('email') }}</font>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">密码</label>
                <div class="col-sm-3">
                    {!! Form::text('password', '', ['class' => 'form-control','placeholder'=>'Password']) !!}
                    <font color="red">{{ $errors->first('password') }}</font>
                    <font color="#8a2be2">为空则不修改</font>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    {!! Form::submit('修改', ['class' => 'btn btn-success']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection