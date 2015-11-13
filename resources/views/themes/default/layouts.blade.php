<!DOCTYPE html>
<html lang="zh-cmn-Hans" prefix="og: http://ogp.me/ns#" class="han-init">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    @yield('header')

    <link rel="stylesheet" href="{{ homeAsset('/vendor/primer-css/css/primer.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/vendor/primer-markdown/dist/user-content.min.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/vendor/octicons/octicons/octicons.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/components/collection.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/components/repo-card.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/sections/repo-list.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/sections/mini-repo-list.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/components/boxed-group.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/globals/common.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/vendor/share.js/dist/css/share.min.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/globals/responsive.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/pages/index.css') }}">

    <link rel="shortcut icon" href="{{ homeAsset('/images/ico/32.png') }}" sizes="32x32" type="image/png"/>
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ homeAsset('/images/ico/72.png') }}" type="image/png"/>
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{{ homeAsset('/images/ico/120.png') }}" type="image/png"/>
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ homeAsset('/images/ico/152.png') }}" type="image/png"/>
    <meta property="og:type" content="article">
    <meta property="og:locale" content="zh_CN" />

    <script src="{{ homeAsset('/vendor/jquery/dist/jquery.min.js') }}"></script>
</head>
<body class="home">
<header class="site-header">
    <div class="container">
        <h1><a href="/">Enda Blog</a></h1>
        <nav class="site-header-nav" role="navigation">

            <a href="/" class=" site-header-nav-item" target="" title="Home">Home</a>


            @if(!empty($navList))
                @foreach($navList as $nav)
                    <a href="{{ $nav->url }}" class="site-header-nav-item">{{ $nav->name }}</a>
                @endforeach

            @endif
            <form class="demo_search" action="{{url('search/keyword')}}" method="get">
                <i class="icon_search" id="open"></i>
                <input class="demo_sinput" type="text" name="keyword" id="search_input" placeholder="输入关键字 回车搜索" />
            </form>
        </nav>


    </div>
</header>

<!-- / header -->


@yield('content')


<footer class="container">
    <div class="site-footer" role="contentinfo">
        <div class="copyright left mobile-block">
            © 2015
            <span >phpyc.com</span>
            <a href="javascript:window.scrollTo(0,0)" class="right mobile-visible">TOP</a>
        </div>

        <ul class="site-footer-links right mobile-hidden">
            <li>
                <a href="javascript:window.scrollTo(0,0)" >TOP</a>
            </li>
        </ul>
        <a href="https://github.com/yccphp/laravel-5-blog" target="_blank" aria-label="view source code">
            <span class="mega-octicon octicon-mark-github" title="GitHub"></span>
        </a>

    </div>
</footer>
<!-- / footer -->
<script src="{{ homeAsset('/vendor/share.js/dist/js/share.min.js') }}"></script>
<script src="{{ homeAsset('/vendor/share.js/dist/js/jquery.qrcode.min.js') }}"></script>
<script src="{{ homeAsset('/js/geopattern.js') }}"></script>
<script src="{{ homeAsset('/js/prism.js') }}"></script>
<link rel="stylesheet" href="{{ homeAsset('/css/globals/prism.css') }}">

<script>
    jQuery(document).ready(function($) {
        // geopattern
        $('.geopattern').each(function(){
            $(this).geopattern($(this).data('pattern-id'));
        });

        $("#open").mouseover(function(){
            $("#search_input").fadeIn(1).animate({width:'300px',opacity:'10'});
            $("#search_input")[0].focus();
            $("#open").fadeOut(10);
        });

        $("#search_input").blur(function(){
            $("#search_input").animate({width:'toggle',opacity:'0.1'}).fadeOut(2);
            $("#open").delay(400).fadeIn(100);
        });
        $('.share-bar').share();
    });
</script>

</body>
</html>
