@extends('backend.setting.common')

@section('content')
        <div class="col-md-10">
            <div class="panel panel-default">
                {!! Notification::showAll() !!}
                <div class="panel-heading">内容管理</div>

                <div class="panel-body">
                    <a class="btn btn-success" href="{{ url('/backend/system/create') }}">创建设置</a>

                    <form action="{{ url('backend/system/store')}}" method="post" class="form-horizontal" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <table class="table table-hover table-top">
                            <tr>
                                <th>#</th>
                                <th>名称</th>
                                <th>值</th>
                                <th class="text-right">操作</th>
                            </tr>

                            @foreach($system as $k=> $v)
                            <tr>
                                <th scope="row">{{ $v->id }}</th>
                                <td>
                                    {{Lang::get('backend_config.'.$v->system_name)}}
                                </td>
                                <td>
                                    {!! Form::text('system['.$v->system_name.']', $v->system_value, ['class' => 'form-control']) !!}
                                </td>
                                <td class="text-right">

                                    <a href="{{ url('/backend/system/delete',['id'=>$v->id]) }}" class="btn btn-danger" >
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        删除
                                    </a>

                                </td>

                            </tr>
                            @endforeach
                        </table>

                        <button type="submit" class="btn btn-success">
                            保存
                        </button>

                    </form>
                </div>
            </div>
        </div>
@endsection
