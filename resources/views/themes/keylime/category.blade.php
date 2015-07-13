@extends('themes.keylime.main')

@section('content')
    <section id="hero" class="light-typo">
        <div id="cover-image" class="image-bg4 animated fadeIn"></div>
        <div class="container welcome-content">
            <div class="middle-text">
                <h1>{{ $category->cate_name }}</h1>
                <h2><b>{{ $category->seo_desc }}</b></h2>
            </div>
        </div>
    </section>

    <section id="breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <ol class="breadcrumb">
                        <li><a href="/">首页</a></li>
                        <li class="active">{{ $category->cate_name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="archives container content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                    @if(!empty($article))
                        @foreach($article as $artKey=>$art)
                            <article class="clearfix @if(count($article) == $artKey+1) last @endif">
                                <div class="post-date">
                                    {{ date('Y-m-d',strtotime($art->created_at)) }} |
                                    <a href="#">{{ $art->user->name }}</a>
                                    <span><a href="{{ route('article.show',array('id'=>$art->id,'#commentList')) }}">{{ $art->status->comment_number }} Comments</a></span>
                                </div>

                                <h2>
                                    <a href="{{ route('article.show',array('id'=>$art->id)) }}">
                                        {{ $art->title }}
                                    </a>
                                </h2>
                                <p>
                                    {{--{{ strCut($art->content,80) }}--}}
                                    {{ strCut(conversionMarkdown($art->content),80) }}
                                    <a class="" href="{{ route('article.show',array('id'=>$art->id)) }}">Read more</a>
                                </p>
                            </article>
                        @endforeach
                    @endif

                        <div class="paging clearfix">
                            {!! $article->render() !!}
                        </div>
            </div>
        </div>
    </div>
@endsection