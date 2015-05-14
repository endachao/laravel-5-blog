<?php namespace App\Http\Controllers;

use App\Model\ArticleStatus;
use Illuminate\Support\Facades\View;
use App\Model\Article;
class HomeController extends Controller {


	/**
	 * 首页
	 * @return Response
	 */
	public function index()
	{
        $article = Article::getNewsArticle();
        $hotArticle = ArticleStatus::getHotArticle(3,false);
        viewInit();
        return homeView('index',array(
            'article'=>$article,
            'hotArticle'=>$hotArticle
        ));
	}

}
