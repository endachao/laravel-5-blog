@extends('themes.keylime.main')

@section('header')
    <title>搜索{{ $keyword }}_{{ systemConfig('title','Enda Blog') }}-Powered By{{ systemConfig('subheading','Enda Blog') }}</title>
    <meta name="keywords" content="{{ systemConfig('seo_key') }}" />
    <meta name="description" content="{{ systemConfig('seo_desc') }}">
@endsection

@section('content')

    <section id="hero" class="light-typo">
        <div id="cover-image" class="image-bg3 animated fadeIn"></div>
        <div class="container welcome-content">
            <div class="middle-text">
                <h1>搜索 {{ $keyword }}</h1>
                <h2>to:{{ $keyword }}</h2>
            </div>
        </div>
    </section>

    <section id="breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('article.index') }}" title="{{ systemConfig('title','Enda Blog') }}">首页</a></li>
                        <li class="active">搜索{{ $keyword }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @if(!empty($article['data']))
        <div class="search container content">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
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
                    <div class="paging clearfix">
                        {!! $article['page']->render() !!}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="search container content">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2>皇上，你想要的臣妾搜不到啊</h2>
                    <p>皇上,你叫臣妾一个月不吃饭,可以。你叫臣妾一个月不上网。可以,你叫臣妾搜索{{ $keyword }},臣妾搜不到啊！</p>
                    <div class="row">
                        <form id="" action="{{url('search/keyword')}}" method="get" class="col-md-6 myform">
                            <div class="input-group">
                                <input name="keyword" placeholder="搜索其它" class="form-control input-lg" type="text" >
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn">搜索</button>
                                        </span>
                            </div>
                        </form>
                        <br>
                    </div>
                    <p class="clearfix"></p>
                    <div class="post-popular">
                        <div class="row hidden-xs">
                            <?php $hotArticle = App\Model\Article::getHotArticle(3)?>
                            @if(!empty($hotArticle))
                                @foreach($hotArticle as $key=>$article)
                                        <div class="col-sm-4 col-md-4">
                                            <a href="{{ url(route('article.show',['id'=>$article->id])) }}" title="{{ $article->title }}" target="_blank">
                                                <img src="{{ getArticleImg($article->pic) }}" class="img-responsive" alt="img2" width="300px" height="150px" title="{{ $article->title }}" alt="{{ $article->title }}">
                                            </a>
                                            <h4 class="text-center">
                                                <a href="{{ url(route('article.show',['id'=>$article->id])) }}" title="{{ $article->title }}" target="_blank">{{ $article->title }}</a>
                                            </h4>
                                            <p class="post-date text-center">{{ date('Y-m-d',strtotime($article->created_at)) }}</p>
                                        </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



@endsection