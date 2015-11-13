<h4>热门文章</h4>
<section class="repo-card">
    <ul class="boxed-group-inner mini-repo-list">

        @if(!empty($hotArticleList))
            @foreach($hotArticleList as $hotArticle)
                <li class="public source ">
                    <a href="{{ route('article.show',array('id'=>$hotArticle->id)) }}"  class="mini-repo-list-item css-truncate" title="{{ $hotArticle->title }}">
                        <span class="repo-and-owner css-truncate-target">
                            {{ $hotArticle->title }}
                        </span>
                    </a>
                </li>
            @endforeach
        @endif

    </ul>
</section>



<div class="widget">
    <h4 class="title">标签云</h4>
    <div class="content tag-cloud">
        @if(!empty($tagList))
            @foreach($tagList as $tag)
                <a href="{{ url('search/tag',['id'=>$tag->id]) }}" title="{{ $tag->name }}">{{ $tag->name }}</a>
            @endforeach
        @endif
    </div>
</div>


<h4>友情链接</h4>
<section class="repo-card">
    <ul class="boxed-group-inner mini-repo-list">
        @if(!empty($linkList))
            @foreach($linkList as $link)
                <li class="public source ">
                    <a href="{{ $link->url }}" target="_blank"  class="mini-repo-list-item css-truncate">
                        <span class="repo-and-owner css-truncate-target">
                            {{ $link->name }}
                        </span>
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
</section>