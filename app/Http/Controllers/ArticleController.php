<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\Tag;
use Illuminate\Http\Request;

use App\Model\ArticleStatus;
use App\Model\Article;
use App\Model\Comment;

class ArticleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
        $article = Article::getNewsArticle();
        $hotArticle = Article::getHotArticle(3);
        viewInit();
        return homeView('index',array(
            'article'=>$article,
            'hotArticle'=>$hotArticle
        ));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
        $article = Article::getArticleModelByArticleId($id);
        $tags = Tag::getTagModelByTagIds($article->tags);
        $authorArticle = Article::getArticleModelByUserId($article->user_id);

        ArticleStatus::updateViewNumber($id);
        $commentList = Comment::getCommentListModel($id);
        $data = array(
            'article'=>$article,
            'tags'=>$tags,
            'authorArticle'=>$authorArticle,
            'commentList'=>$commentList
        );
        viewInit();
        return homeView('article',$data);
	}

}
