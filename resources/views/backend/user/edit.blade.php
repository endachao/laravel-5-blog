@extends('backend.user.common')

@section('content')
<div class="col-md-10">
    <div class="panel panel-default">
        <div class="panel-heading">修改用户</div>

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
            {!! Form::model($user, ['route' => ['backend.user.update', $user->id], 'method' => 'put','class'=>'form-horizontal','enctype'=>'multipart/form-data']) !!}

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
                <label  class="col-sm-2 control-label">头像</label>
                <div class="col-sm-3">
                    {!! Form::file('photo') !!}
                    <font color="red">{{ $errors->first('photo') }}</font>
                    <br />
                    <div class="row-sm-2">
                        @if(!empty($user->photo))
                            <img src="{{ asset('uploads'.'/'.$user->photo) }}" width="300px"/>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label  class="col-sm-2 control-label">描述</label>
                <div class="col-sm-5 editor">
                    @include('editor::head')
                    {!! Form::textarea('desc', $user->desc, ['class' => 'form-control','id'=>'myEditor']) !!}
                    <font color="red">{{ $errors->first('desc') }}</font>
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