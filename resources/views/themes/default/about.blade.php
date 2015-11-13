@extends('themes.default.layouts')

@section('header')
    <title>{{ $userInfo->name }}介绍_{{ systemConfig('title','Enda Blog') }}-Powered By{{ systemConfig('subheading','Enda Blog') }}</title>
    <meta name="keywords" content="{{ $userInfo->name }},{{ systemConfig('seo_key') }}" />
    <meta name="description" content="{!! str_limit(preg_replace('/\s/', '',strip_tags(conversionMarkdown($userInfo->desc))),100) !!}">
@endsection

@section('content')
    <section class="banner">
        <div class="collection-head">
            <div class="container">
                <div class="collection-title">
                    <h1 class="collection-header">{{ $userInfo->name }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="container content">
        <div class="columns">
            <div class="column three-fourths">
                <article class="article-content markdown-body">
                    {!! conversionMarkdown($userInfo->desc) !!}
                </article>
                <div class="comment">
                    <div class="comments">
                        <div id="disqus_thread"></div>
                        <script type="text/javascript">
                            var disqus_shortname = "{{ config('disqus.disqus_shortname') }}";
                            (function() {
                                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                            })();
                        </script>
                        <noscript>Please enable JavaScript to view the &lt;a href="http://disqus.com/?ref_noscript"&gt;comments powered by Disqus.&lt;/a&gt;</noscript>
                    </div>
                </div>
            </div>

            <div class="column one-fourth">
                <div id="author" class="clearfix">
                    <img class="img-circle" src="{{ asset('uploads'.'/'.$userInfo->photo) }}" height="96" width="96" alt="{{ $userInfo->name }}" title="{{ $userInfo->name }}">
                </div>
                @include('themes.default.right')
            </div>
        </div>
    </section>



@endsection