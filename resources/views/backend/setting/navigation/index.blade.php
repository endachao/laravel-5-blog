@extends('backend.setting.common')

@section('content')
        <div class="col-md-10">
            <div class="panel panel-default">
                {!! Notification::showAll() !!}
                <div class="panel-heading">内容管理</div>

                <div class="panel-body">
                    <a class="btn btn-success" href="{{ URL::route('backend.nav.create')}}">添加导航</a>

                    <table class="table table-hover table-top">
                        <tr>
                            <th>#</th>
                            <th>名称</th>
                            <th>地址</th>
                            <th>创建时间</th>
                            <th class="text-right">操作</th>
                        </tr>

                        @foreach($list as $k=> $v)
                        <tr>
                            <th scope="row">{{ $v->id }}</th>
                            <td>{{ $v->html }}{{ $v->name }}</td>
                            <td>{{ $v->url }}</td>
                            <td>{{ $v->created_at }}</td>
                            <td class="text-right">


                                <a href="{{ url(route('backend.nav.create',['parentId'=>$v->id])) }} " style="margin-right: 10px;" class="btn btn-info"  >
                                    添加导航
                                </a>


                                {!! Form::open([
                                    'route' => array('backend.nav.destroy', $v->id),
                                    'method' => 'delete',
                                    'class'=>'btn_form'
                                ]) !!}

                                <button type="submit" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    删除
                                </button>

                                {!! Form::close() !!}

                                {!! Form::open([
                                    'route' => array('backend.nav.edit', $v->id),
                                    'method' => 'get',
                                    'class'=>'btn_form'
                                ]) !!}

                                <button type="submit" class="btn btn-info">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                    修改
                                </button>
                                {!! Form::close() !!}

                            </td>

                        </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
@endsection
