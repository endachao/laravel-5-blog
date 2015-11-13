@extends('backend.setting.common')

@section('content')
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">添加友情链接</div>

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
                    {!! Form::open(['route' => 'backend.links.store', 'method' => 'post','class'=>'form-horizontal']) !!}

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-3">
                                {!! Form::text('sequence', '', ['class' => 'form-control','placeholder'=>'sequence']) !!}
                                <font color="red">{{ $errors->first('sequence') }}</font>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">链接名称</label>
                            <div class="col-sm-3">
                                {!! Form::text('name', '', ['class' => 'form-control','placeholder'=>'name']) !!}
                                <font color="red">{{ $errors->first('name') }}</font>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">链接地址</label>
                            <div class="col-sm-3">
                                {!! Form::text('url', '', ['class' => 'form-control','placeholder'=>'url']) !!}
                                <font color="red">{{ $errors->first('url') }}</font>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {!! Form::submit('创建', ['class' => 'btn btn-success']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
@endsection
