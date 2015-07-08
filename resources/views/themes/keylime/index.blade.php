@extends('themes.keylime.main')

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
                                <a href="#">{{ $art->user->name }}</a>
                                <span><a href="#">{{ $art->status->comment_number }} Comments</a></span>
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

                    <div class="post-popular">

                        <div class="row hidden-xs">

                            @if(!empty($hotArticle))
                            @foreach($hotArticle as $key=>$article)
                                <div class="col-sm-4 col-md-4">
                                    <a href="post-video.html">
                                        <img src="{{ asset('uploads/'.$article->article->pic) }}" class="img-responsive" alt="img2" width="300px" height="150px">
                                    </a>
                                    <h4 class="text-center">
                                        <a href="post-video.html">{{ $article->article->title }}</a>
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