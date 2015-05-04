@extends('backend.content.common')

@section('content')
        <div class="col-md-10">
            <div class="panel panel-default">
                {!! Notification::showAll() !!}
                <div class="panel-heading">内容管理</div>

                <div class="panel-body">
                    <a class="btn btn-success" href="{{ URL::route('backend.article.create')}}">写文章</a>

                    <table class="table table-hover table-top">
                        <tr>
                            <th>#</th>
                            <th>title</th>
                            <th>所属分类</th>
                            <th>作者</th>
                            <th>游览次数</th>
                            <th>评论数</th>
                            <th>创建时间</th>
                            <th class="text-right">操作</th>
                        </tr>

                        @foreach($article as $k=> $v)
                        <tr>
                            <th scope="row">{{ $v->id }}</th>
                            <td>{{ $v->title }}</td>
                            <td>{{ App\Model\Category::getCategoryNameByCatId($v->cate_id) }}</td>
                            <td>{{ App\User::getUserNameByUserId($v->user_id) }}</td>
                            <td>{{ $v->status->view_number }}</td>
                            <td>{{ $v->status->comment_number }}</td>
                            <td>{{ $v->created_at }}</td>
                            <td class="text-right">


                                {!! Form::open([
                                'route' => array('backend.article.destroy', $v->id),
                                'method' => 'delete',
                                'class'=>'btn_form'
                                ]) !!}

                                <button type="submit" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    删除
                                </button>

                                {!! Form::close() !!}

                                {!! Form::open([
                                    'route' => array('backend.article.edit', $v->id),
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
                {!! $article->render() !!}
            </div>

        </div>
@endsection
