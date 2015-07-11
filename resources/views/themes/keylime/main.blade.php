<!doctype html>
<html>
<head>
    <title>Key Lime</title>
    <meta charset="utf-8">
    <meta name="description" content="Key Lime Responsive HTML5/CSS3 Template from angelostudio.net">
    <meta name="author" content="袁超">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="{{ homeAsset('/css/googleCss.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ homeAsset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/icons.css') }}">
    <link rel="stylesheet" href="{{ homeAsset('/css/animate.min.css') }}">
    <link rel="shortcut icon" href="{{ homeAsset('/img/ico/32.png') }}" sizes="32x32" type="image/png"/>
    <link rel="apple-touch-icon-precomposed" href="{{ homeAsset('/img/ico/60.png" type="image/png') }}"/>
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ homeAsset('/img/ico/72.png') }}" type="image/png"/>
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{{ homeAsset('/img/ico/120.png') }}" type="image/png"/>
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ homeAsset('/img/ico/152.png') }}" type="image/png"/>
    <script type="text/javascript" src="{{ homeAsset('/js/jquery-1.9.1.min.js') }}"></script>
    <!--[if lt IE 9]>
    <script src="{{ homeAsset('/js/html5shiv.js') }}"></script>
    <script src="{{ homeAsset('/js/respond.min.js') }}"></script>
    <![endif]-->
</head>
<body id="home">

@include('themes.keylime.menu')
<div id="wrap">
    <div id="main-nav" class="">
        <div class="container">
            <div class="nav-header">
                <a class="nav-brand" href="index-2.html"><i class="icon-lime"></i>Enda Blog</a>
                <a class="menu-link nav-icon" href="#"><i class="icon-menu2"></i></a>
            </div>
        </div>
    </div>
@yield('content')

<footer>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-md-4 footer-widget">
                    <h3>Statistics</h3>

                    <span>指点江山，激扬代码，粪土当年万户侯</span>

                    <div class="stats">
                        <div class="line">
                            <span class="counter">
                                @if(!empty($dataCount['article']))
                                    {{ $dataCount['article'] }}
                                @else
                                    0
                                @endif

                            </span>
                            <span class="caption">文章</span>
                        </div>
                        <div class="line">
                            <span class="counter">
                                @if(!empty($dataCount['comment']))
                                    {{ $dataCount['comment'] }}
                                @else
                                    0
                                @endif
                            </span>
                            <span class="caption">评论</span>
                        </div>
                        <div class="line">
                            <span class="counter">
                                @if(!empty($dataCount['visit']))
                                    {{ $dataCount['visit'] }}
                                @else
                                    0
                                @endif
                            </span>
                            <span class="caption">文章总游览</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-md-4 footer-widget">
                    <h3>Recent posts</h3>
                    <div class="post-recent-widget">
                        <div class="row">
                            <div class="col-sm-12">

                                @if(!empty($recentArticle))
                                    @foreach($recentArticle as $article)
                                        <div class="media">
                                            <a class="pull-left" href="post-video.html">
                                                <img class="media-object" src="{{ asset('uploads').'/'.$article->pic }}" width="80" alt="{{ $article->title }}"></a>
                                            <div class="media-body">
                                                <h4 class="media-heading">
                                                    <a href="post-video.html">{{ $article->title }}</a>
                                                </h4>
                                                <p class="post-date">{{ date('Y-m-d',strtotime($article->created_at)) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif


                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-md-4 footer-widget clearfix">
                    <h3>Tags</h3>
                    <ul class="tags">
                        @if(!empty($hotTags))
                            @foreach($hotTags as $tag)
                                <li>
                                    <a href="#">{{ $tag->name }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <p class="pull-left">Powered by <a href="#">Yuan Chao.</a></p>
            <ul class="social-links pull-right">
                <li><a href="#link"><i class="icon-twitter"></i></a></li>
                <li><a href="#link"><i class="icon-facebook"></i></a></li>
                <li><a href="#link"><i class="icon-googleplus"></i></a></li>
            </ul>
        </div>
    </div>
</footer>
</div>


<script type="text/javascript" src="{{ homeAsset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ homeAsset('/js/placeholders.min.js') }}"></script>
<script type="text/javascript" src="{{ homeAsset('/js/wow.min.js') }}"></script>
<script type="text/javascript" src="{{ homeAsset('/js/custom.js') }}"></script>
<script type="text/javascript" src="{{ homeAsset('/js/ga.js') }}"></script>
</body>

</html>