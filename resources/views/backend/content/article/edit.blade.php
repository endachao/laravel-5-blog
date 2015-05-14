@extends('backend.content.common')

@section('content')


<style type="text/css">

    .editor-wrapper {
        max-width: 680px;
        padding: 10px;
        margin: 60px auto;
    }
</style>
<script type="text/javascript" src="{{ asset('/plugin/markdown/marked.js') }}"></script>
<script type="text/javascript" src="{{ asset('/plugin/markdown/editor.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/plugin/markdown/editor.css') }}">

<!-- Tokenfield CSS -->
<link href="{{ asset('/plugin/tags/css/bootstrap-tokenfield.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('/plugin/tags/css/jquery-ui.css ') }}" type="text/css" rel="stylesheet">

        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">编辑文章</div>

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
                    {!! Form::model($article, ['route' => ['backend.article.update', $article->id], 'method' => 'put','class'=>'form-horizontal','enctype'=>'multipart/form-data']) !!}


                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-3">
                                {!! Form::text('title', $article->title, ['class' => 'form-control','placeholder'=>'title']) !!}
                                <font color="red">{{ $errors->first('title') }}</font>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">所属分类</label>
                            <div class="col-sm-3">
                                {!! Form::select('cate_id', $catArr , null , ['class' => 'form-control']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">选择已有标签</label>
                            <div class="col-sm-3">
                                {!! Form::text('tags', '', ['class' => 'form-control','placeholder'=>'tags','id'=>'tags']) !!}
                                <font color="#deb887">点击选择标签库里面的标签</font>
                                <font color="red">{{ $errors->first('tags') }}</font>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">创建新的标签</label>
                            <div class="col-sm-3">
                                {!! Form::text('new_tags', '', ['class' => 'form-control','placeholder'=>'tags']) !!}
                                <font color="#deb887">用半角逗号分割</font>
                                <font color="red">{{ $errors->first('new_tags') }}</font>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">封面图</label>
                            <div class="col-sm-3">
                                {!! Form::file('pic', ['class' => 'form-control']) !!}
                                <font color="red">{{ $errors->first('pic') }}</font>
                                @if(!empty($article->pic))
                                    <img  src="{{ asset('/uploads').'/'.$article->pic }}"/>
                                @endif
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">内容</label>
                            <div class="col-sm-7">
                                {!! Form::textarea('content', '', ['class' => 'form-control','id'=>'editor']) !!}
                                <font color="red">{{ $errors->first('content') }}</font>
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

<script type="text/javascript" src="{{ asset('/plugin/markdown/zepto.min.js') }}"></script>
<script type="text/javascript">
    (function($) {
        $('#editor').load('{{ URL::route("backend.article.show",["id"=>$article->id])}}',
            function(data) {
                var editor = new Editor();
                editor.render();
            }
        );
    })(Zepto);
</script>

<script type="text/javascript" src="{{ asset('/plugin/tags/jquery-ui.js ') }}"></script>
<script type="text/javascript" src="{{ asset('/plugin/tags/bootstrap-tokenfield.js ') }}" charset="UTF-8"></script>

<script type="text/javascript">
    $('#tags').tokenfield({
        autocomplete: {
            source: <?php echo  \App\Model\Tag::getAllTagsString()?>,
            delay: 100

        },
        showAutocompleteOnFocus: !0,
        delimiter: [","],
        tokens: <?php echo  \App\Model\Tag::getTagsNameByTagsIds($article->tags)?>
    })
</script>
@endsection
