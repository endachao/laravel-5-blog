<?php namespace App\Http\Controllers;

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
        if(empty($keyword)){
            return redirect()->route('article.index');
        }
        $article = Article::getArticleListByKeyword($keyword);


        viewInit();
        return homeView('search', [
            'article' => $article,
            'keyword' => $keyword
        ]);

    }

    public function getTag($id)
    {

        $article = Article::getArticleListByTagId($id);

        viewInit();
        return homeView('searchTag', [
            'article' => $article,
            'tagName'=> Tag::getTagNameByTagId($id)
        ]);
    }

}
