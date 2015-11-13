<?php namespace App\Http\Controllers;

use App\Components\EndaPage;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\Article;
use App\Model\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SearchController extends Controller
{

    public function getKeyword(Request $request)
    {
        $keyword = $request->input('keyword');
        if (empty($keyword)) {
            return redirect()->route('article.index');
        }
        $article = Article::getArticleListByKeyword($keyword);

        $page = new EndaPage($article['page']);
        viewInit();
        return homeView('search', [
            'articleList' => $article,
            'keyword' => $keyword,
            'page' => $page
        ]);

    }

    public function getTag($id)
    {

        $article = Article::getArticleListByTagId($id);
        $page = new EndaPage($article['page']);
        viewInit();
        return homeView('searchTag', [
            'articleList' => $article,
            'tagName' => Tag::getTagNameByTagId($id),
            'page' => $page
        ]);
    }

}
