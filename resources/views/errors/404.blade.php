@extends('themes.keylime.main')

@section('header')
    <title>404_{{ systemConfig('title','Enda Blog') }}-Powered By{{ systemConfig('subheading','Enda Blog') }}</title>
    <meta name="keywords" content="{{ systemConfig('seo_key') }}" />
    <meta name="description" content="{{ systemConfig('seo_desc') }}">
@endsection

@section('content')
<section id="hero" class="light-typo">
    <div id="cover-image" class="image-bg3 animated fadeIn"></div>
    <div class="container welcome-content">
        <div class="middle-text">
            <h1>哎呀，访问的页面去冥王星旅游去了</h1>
            <h2>先去看看其它的吧</h2>
            <a class="btn" href="{{ url('/') }}" title="{{ systemConfig('title','Enda Blog') }}">返回首页</a><br>
        </div>
    </div>
</section>

<div class="container content">
    <div class="post-popular">
        <div class="row hidden-xs">
            {{ viewInit() }}
            <?php $hotArticle = App\Model\ArticleStatus::getHotArticle(3,false)?>
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
@endsection