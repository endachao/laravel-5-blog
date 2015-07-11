@extends('backend.content.common')

@section('content')

    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">查看评论</div>

            {!! Notification::showAll() !!}
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
                    <table class="detail-view table table-striped table-condensed" id="yw0">
                        <tr>
                            <th>id</th>
                            <td>{{ $commentInfo->id }}</td>
                        </tr>
                        <tr>
                            <th>用户</th>
                            <td>{{ $commentInfo->username }}</td>
                        </tr>
                        <tr>
                            <th>邮箱</th>
                            <td>{{ $commentInfo->email }}</td>
                        </tr>
                        <tr>
                            <th>时间</th>
                            <td>{{ $commentInfo->created_at }}</td>
                        </tr>
                        <tr>
                            <th>评论于</th>
                            <td>
                                {{ $commentInfo->article->title }}
                                <a href="{{ url(route('article.show',['id'=>$commentInfo->el_id])) }}" target="_blank" >点击查看</a>
                            </td>
                        </tr>
                        <tr>
                            <th>回复or评论</th>
                            <td>
                                {{ $commentInfo->parent_id == 0?'评论':'回复用户：'.\App\model\Comment::getCommentReplyUserNameByCommentId($commentInfo->parent_id).'， 的评论' }}
                                @if($commentInfo->parent_id != 0)
                                    <a href="{{ url(route('backend.comment.show',['id'=>$commentInfo->parent_id])) }}" target="_blank" >点击查看</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>内容</th>
                            <td>
                                {{ $commentInfo->content }}
                                <br />
                            </td>
                        </tr>

                    </table>
                    <a href="{{ url(route('backend.comment.create',['id'=>$commentInfo->id])) }}" target="_blank" class="btn btn-info" >
                        点击回复
                    </a>
                </div>


    </div>
@endsection
