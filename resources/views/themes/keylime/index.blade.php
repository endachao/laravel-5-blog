@extends('themes.keylime.main')

@section('header')
    <title>{{ systemConfig('title','Enda Blog') }} -Powered By  {{ systemConfig('subheading','Enda Blog') }}</title>
    <meta name="keywords" content="{{ systemConfig('seo_key') }}" />
    <meta name="description" content="{{ systemConfig('seo_desc') }}">
@endsection
@section('content')
        <section id="hero" class="light-typo">
            <div id="cover-image" class="image-bg animated fadeIn"></div>
            <div class="container welcome-content">
                <div class="middle-text">
                    <h1>HELLO, I AM YuanChao</h1>
                    <h2><b>Enda Blog</b> is a blog theme built with Bootstrap <br>by <b>Angelo Studio</b></h2>
                    <a class="btn smooth-scroll" href="#start">Get Stated</a>
                </div>
            </div>
        </section>

        <div id="start" class="container content">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">

                    @if(!empty($article))
                        @foreach($article as $artKey=>$art)
                        <article class="clearfix @if(count($article) == $artKey+1) last @endif">
                            <div class="post-date">
                               {{ date('Y-m-d',strtotime($art->created_at)) }} |
                                <a href="{{ url(route('about.show',['id'=>$art->user->id])) }}" title="{{ $art->user->name }}" target="_blank" >{{ $art->user->name }}</a>
                                <span><a href="{{ route('article.show',array('id'=>$art->id,'#commentList')) }}" title="{{ $art->title }}" target="_blank">{{ $art->status->comment_number }} Comments</a></span>
                            </div>

                            <h2>
                                <a href="{{ route('article.show',array('id'=>$art->id)) }}" title="{{ $art->title }}" target="_blank">
                                    {{ $art->title }}
                                </a>
                            </h2>
                            <p>
                                {{ strCut(conversionMarkdown($art->content),80) }}
                                <a class="" href="{{ route('article.show',array('id'=>$art->id)) }}" title="{{ $art->title }}" target="_blank">Read more</a>
                            </p>
                        </article>
                        @endforeach
                    @endif

                    <div class="paging clearfix">
                        {!! $article->render() !!}
                    </div>

                    <div class="post-popular">

                        <div class="row hidden-xs">

                            @if(!empty($hotArticle))
                            @foreach($hotArticle as $key=>$article)
                                <div class="col-sm-4 col-md-4">
                                    <a href="{{ url(route('article.show',['id'=>$article->id])) }}" title="{{ $article->article->title }}" target="_blank">
                                        <img src="{{ asset('uploads/'.$article->article->pic) }}" class="img-responsive" alt="img2" width="300px" height="150px" title="{{ $article->article->title }}" alt="{{ $article->article->title }}">
                                    </a>
                                    <h4 class="text-center">
                                        <a href="{{ url(route('article.show',['id'=>$article->id])) }}" title="{{ $article->article->title }}" target="_blank">{{ $article->article->title }}</a>
                                    </h4>
                                    <p class="post-date text-center">{{ date('Y-m-d',strtotime($article->article->created_at)) }}</p>
                                </div>
                            @endforeach
                            @endif
                        </div>

                    </div>

                </div>
            </div>
            <!-- end row -->
        </div>
@endsection