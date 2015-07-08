@extends('themes.keylime.main')

@section('content')


        <section id="hero" class="light-typo">
            <div id="cover-image" class="image-bg2 animated fadeIn"></div>
            <div class="container welcome-content">
                <div class="middle-text">
                    <h1>{{ $article->title }}</h1>
                    <h2>{{ $article->user->name }}</h2>
                </div>
            </div>
        </section>

        <section id="breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <ol class="breadcrumb">
                            <li><a href="{{ route('article.index') }}">首页</a></li>
                            <li><a href="#">{{ $article->category->cate_name }}</a></li>
                            <li class="active">{{ $article->title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="container content">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">

                    <div class="post-date">
                        {{ date('Y-m-d',strtotime($article->created_at)) }} |
                        <a href="#">{{ $article->user->name }} </a>
                        <span>
                            <a href="#">{{ $article->status->comment_number }} Comments</a>
                        </span>
                    </div>

                    {!! conversionMarkdown($article->content) !!}

                    <div class="post-date">
                        tags |
                        @if(!empty($tags))
                            @foreach($tags as $key=>$tag)
                                <a href="#">{{ $tag->name }}</a>@if(count($tags) != $key+1) , @endif
                            @endforeach
                        @endif
                    </div>


                    <ul class="social-links outline text-center">
                        <li><a href="#link"><i class="icon-twitter"></i></a></li>
                        <li><a href="#link"><i class="icon-facebook"></i></a></li>
                        <li><a href="#link"><i class="icon-googleplus"></i></a></li>
                    </ul>

                    <div id="author" class="clearfix">
                        <img class="img-circle" alt="" src="{{ asset('uploads'.'/'.$article->user->photo) }}" height="96" width="96">
                        <div class="author-info">
                            <h3>{{ $article->user->name }}</h3>
                            <p>
                                {{ $article->user->desc }}
                            </p>
                        </div>
                    </div>


                    <div class="post-popular">
                        <div class="row hidden-xs">
                            @if(!empty($authorArticle))

                                @foreach($authorArticle as $articleModel)

                            <div class="col-sm-4 col-md-4">
                                <a href="{{ route('article.show',array('id'=>$articleModel->id)) }}">
                                    <img src="{{ asset('uploads'.'/'.$articleModel->pic) }}" class="img-responsive" alt="{{ $articleModel->title }}" style="height: 200px;"></a>
                                <h4 class="text-center">
                                    <a href="{{ route('article.show',array('id'=>$articleModel->id)) }}">
                                        {{ $articleModel->title }}
                                    </a>
                                </h4>
                                <p class="post-date text-center">
                                    {{ date('Y-m-d',strtotime($articleModel->created_at)) }}
                                </p>
                            </div>

                                @endforeach
                            @endif
                        </div>

                    </div>

                    <h3>{{ $article->status->comment_number }} 评论</h3>
                    <div class="media" id="commentList">
                        <hr>

                        @if(!empty($commentList))
                            @foreach($commentList as $commentModel)
                                <div class="media">
                                    <a class="pull-left avatar" href="#">
                                        <img class="media-object img-circle" src="{{ App\Model\Comment::getHeaderImg() }}" width="40" height="40" alt="">
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="#addComment" onclick="updateParentId('{{$commentModel->id}}','{{$commentModel->username}}')">{{ $commentModel->username }}</a>
                                            <span>
                                                {{ date('Y-m-d',strtotime($commentModel->created_at)) }} | <a href="#addComment" onclick="updateParentId('{{$commentModel->id}}','{{$commentModel->username}}')">回复</a>
                                            </span>
                                        </h4>
                                        <p>
                                            @if($commentModel->parent_id != 0)
                                                <a href="javascript:void(0)">
                                                    {{ '@'.App\Model\Comment::getCommentReplyUserNameByCommentId($commentModel->parent_id) }}
                                                </a>
                                            @endif
                                            {{ $commentModel->content }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>

                    <div id="comments_pagination">
                        {!! $commentList->fragment('commentList')->render() !!}
                    </div>

                    <h3 id="addComment">Add a new comment</h3>
                    {!! Notification::showAll() !!}
                    {!!  Form::open(['route' => 'comment.store', 'method' => 'post','class'=>'myform','id'=>'mycomment']) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 wow fadeInUp" >
                                <div class="form-group">
                                    <label class="control-label" for="contact-message">吐槽？提问？</label>
                                    <div class="controls">
                                        {!!
                                            Form::textarea(
                                                'content',
                                                '',
                                                [
                                                    'placeholder'=>'吐槽？提问',
                                                    'class'=>'form-control input-lg requiredField',
                                                    'rows'=>'3',
                                                    'data-error-empty'=>'请输入评论内容',
                                                    'id'=>'content'

                                                ]
                                            )
                                        !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">姓名</label>
                                            <div class="controls">
                                                {!!
                                                    Form::text(
                                                        'username',
                                                        '',
                                                        [
                                                            'placeholder'=>'骚年，告诉我的你的名字',
                                                            'class'=>'form-control input-lg requiredField',
                                                            'data-error-empty'=>'无名氏？',
                                                            'id'=>'username'
                                                        ]
                                                    )
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="contact-mail">邮箱</label>
                                            <div class=" controls">
                                                {!!
                                                    Form::email(
                                                        'email',
                                                        '',
                                                        [
                                                            'placeholder'=>'邮箱',
                                                            'class'=>'form-control input-lg requiredField',
                                                            'data-error-empty'=>'喂！不告诉我你的邮箱怎么给你回信？',
                                                            'id'=>'email'
                                                        ]
                                                    )
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">验证码</label>
                                            <div class="controls">
                                                {!!
                                                    Form::text
                                                    (
                                                        'captcha',
                                                        '',
                                                        [
                                                            'placeholder'=>'验证码',
                                                            'class'=>'form-control input-lg requiredField',
                                                            'data-error-empty'=>'你是机器人嘛?',
                                                            'id'=>'captcha'
                                                        ]
                                                    )
                                                !!}

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3">
                                        <a onclick="javascript:re_captcha();" href="javascript:void(0)" >
                                            <img src="{{ homeAsset('/img/show.jpg') }}"  alt="验证码" title="刷新图片" width="100" height="40" id="verifyCode"  border="0" display>
                                        </a>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            {!! Form::hidden('parent_id', 0,['id'=>'parent_id']) !!}
                                            {!! Form::hidden('el_id', $article->id) !!}
                                            <button name="submit" type="submit" class="btn btn-block" data-error-message="Error!" data-sending-message="Sending..." data-ok-message="Comment Sent">Send Comment</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close()  !!}

                </div>
            </div>
        </div>
<script>
    function re_captcha() {
        $url = "{{ URL('public/captcha') }}";
        $url = $url + "/" + Math.random();
        document.getElementById('verifyCode').src=$url;
    }

    function updateParentId(parentId,username){
        $('#content').attr('placeholder','@'+username);
        $('#parent_id').val(parentId);
    }

</script>
@endsection