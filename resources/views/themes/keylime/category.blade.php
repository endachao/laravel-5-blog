@extends('themes.keylime.main')

@section('header')
    <title>{{ $category->cate_name }}_{{ systemConfig('title','Enda Blog') }} -Powered By  {{ systemConfig('subheading','Enda Blog') }}</title>
    <meta name="keywords" content="{{ $category->cate_name }},{{ $category->seo_key }},{{ systemConfig('seo_key') }}" />
    <meta name="description" content="{{ $category->seo_desc }}">
@endsection

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
                        <li><a href="{{ route('article.index') }}" title="{{ systemConfig('title','Enda Blog') }}">首页</a></li>
                        <li class="active">{{ $category->cate_name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="archives container content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                    @if(!empty($article['data']))
                        @foreach($article['data'] as $artKey=>$art)
                            <article class="clearfix @if(count($article['data']) == $artKey+1) last @endif">
                                <div class="post-date">
                                    {{ date('Y-m-d',strtotime($art->created_at)) }} |
                                    <a href="{{ url(route('about.show',['id'=>$art->user->id])) }}" title="{{ $art->user->name }}" target="_blank"> {{ $art->user->name }}</a>
                                    <span><a href="{{ route('article.show',array('id'=>$art->id)) }}#disqus_thread" title="{{ $art->title }}" target="_blank" >0 Comments</a></span>
                                </div>

                                <h2>
                                    <a href="{{ route('article.show',array('id'=>$art->id)) }}" title="{{ $art->title }}" target="_blank">
                                        {{ $art->title }}
                                    </a>
                                </h2>
                                <p>
                                    {{ strCut(conversionMarkdown($art->content),80) }}
                                    <a class="" href="{{ route('article.show',array('id'=>$art->id)) }}" title="{{ $art->title }}" target="_blank" >Read more</a>
                                </p>
                            </article>
                        @endforeach
                    @endif

                        <div class="paging clearfix">
                            {!! $article['page']->render() !!}
                        </div>
            </div>
        </div>
    </div>
@endsection