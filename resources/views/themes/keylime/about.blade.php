@extends('themes.keylime.main')

@section('header')
    <title>{{ $userInfo->name }}介绍_{{ systemConfig('title','Enda Blog') }}-Powered By{{ systemConfig('subheading','Enda Blog') }}</title>
    <meta name="keywords" content="{{ $userInfo->name }},{{ systemConfig('seo_key') }}" />
    <meta name="description" content="{!! str_limit(preg_replace('/\s/', '',strip_tags(conversionMarkdown($userInfo->desc))),100) !!}">
@endsection

@section('content')
    <section id="hero" class="light-typo">
        <div id="cover-image" class="image-bg3 animated fadeIn"></div>
        <div class="container welcome-content">
            <div class="middle-text">
                <h1>关于 {{ $userInfo->name }}</h1>
            </div>
        </div>
    </section>

    <section id="breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('article.index') }}" title="{{ systemConfig('title','Enda Blog') }}">首页</a></li>
                        <li class="active">关于 {{ $userInfo->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="container content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="row">
                    <div class="col-sm-9 col-md-8 ">
                        {!! conversionMarkdown($userInfo->desc) !!}
                    </div>
                    <div class="col-sm-3 col-md-4">
                        <img src="{{ asset('uploads'.'/'.$userInfo->photo) }}" class="img-responsive img-circle about-portrait" alt="{{ $userInfo->name }}" title="{{ $userInfo->name }}" width="300" height="300">
                        <ul class="social-links outline text-center">
                            <li><a href="http://weibo.com/28ex" target="_blank"><i class="icon-weibo"></i></a></li>
                            <li><a href="http://t.qq.com/YING7598459999" target="_blank"><i class="icon-tencent-weibo"></i></a></li>
                            <li><a href="https://github.com/yccphp" target="_blank"><i class="icon-github4"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="post-popular">
                    <div class="row hidden-xs">
                        @if(!empty($userArticle))
                            @foreach($userArticle as $articleModel)
                                <div class="col-sm-4 col-md-4">
                                    <a href="{{ route('article.show',array('id'=>$articleModel->id)) }}" title="{{ $articleModel->title }}" target="_blank">
                                        <img src="{{ getArticleImg($articleModel->pic) }}" class="img-responsive" title="{{ $articleModel->title }}" alt="{{ $articleModel->title }}" style="height: 200px;"></a>
                                    <h4 class="text-center">
                                        <a href="{{ route('article.show',array('id'=>$articleModel->id)) }}" title="{{ $articleModel->title }}" target="_blank">
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
            </div>
        </div>
    </div>
@endsection