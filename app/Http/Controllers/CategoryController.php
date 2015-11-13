<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\Article;
use App\Model\Category;
use Illuminate\Http\Request;
use App\Components\EndaPage;
class CategoryController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
        viewInit();
        $category = Category::getCatInfoModelByAsName($id);
        if (empty($category)) {
            return redirect(url(route('article.index')));
        }
        $article = Article::getArticleListByCatId($category->id, 10);
        $page = new EndaPage($article['page']);
        return homeView('category', [
            'category' => $category,
            'articleList' => $article,
            'page' => $page
        ]);
    }

}
