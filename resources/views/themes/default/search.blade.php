@extends('themes.default.layouts')

@section('header')
    <title>搜索{{ $keyword }}_{{ systemConfig('title','Enda Blog') }}-Powered By{{ systemConfig('subheading','Enda Blog') }}</title>
    <meta name="keywords" content="{{ systemConfig('seo_key') }}" />
    <meta name="description" content="{{ systemConfig('seo_desc') }}">
@endsection

@section('content')
<section class="banner">
    <div class="collection-head">
        <div class="container">
            <div class="collection-title">
                <h1 class="collection-header">{{ $keyword }}</h1>
            </div>
            <span class="meta-info">
                Cool 善于搜索，才能学习更多的东西哦～
            </span>
        </div>
    </div>
</section>

<!-- /.banner -->
<section class="container content">
    <div class="columns">
        <div class="column two-thirds" >
            <ol class="repo-list">
                @if(!empty($articleList['data']))
                    @foreach($articleList['data'] as $article)
                        <li class="repo-list-item">
                            <h3 class="repo-list-name">
                                <a href="{{ route('article.show',array('id'=>$article->id)) }}" title="{{ $article->title }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <p class="repo-list-description">
                                {{ strCut(conversionMarkdown($article->content),80) }}
                            </p>
                            <p class="repo-list-meta">
                                <span class="octicon octicon-calendar"></span>{{ $article->created_at->format('Y-m-d') }}
                            </p>
                        </li>
                    @endforeach

                @else

                    <li class="repo-list-item">
                        <h3 class="repo-list-name">
                            暂时没搜到关于关键字 <span style="color: #f4645f">{{ $keyword }}</span> 的内容，换个关键字试试吧～
                        </h3>
                    </li>
                @endif
            </ol>
        </div>
        <div class="column one-third">
            @include('themes.default.right')
        </div>
    </div>
    <div class="pagination text-align">
        <nav>
           {!! $articleList['page']->appends(['keyword' => $keyword])->render($page) !!}
        </nav>
    </div>
    <!-- /pagination -->
</section>
<!-- /section.content -->

@endsection