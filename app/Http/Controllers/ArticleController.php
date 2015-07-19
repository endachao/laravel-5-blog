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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
