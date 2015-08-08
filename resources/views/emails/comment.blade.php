Hi，{{ $username }}：
<br />
您在 {{ systemConfig('title','Enda Blog') }} 上提交的评论被回复啦，赶紧看看去吧！
<br />
<a href="{{ url(route('article.show',['id'=>$id])) }}#commentList">点击查看</a>