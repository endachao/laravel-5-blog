<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use Input, Notification, Auth, Request, Cache;
use App\Model\Article;
use App\Model\Category;
use App\Model\ArticleStatus;
use App\Http\Requests\ArticleForm;
use App\Model\Tag;

class ArticleController extends Controller
{

    public function __construct()
    {
        conversionClassPath(__CLASS__);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return backendView('index', ['article' => Article::orderBy('id', 'DESC')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $catArr = Category::getCategoryTree();
        unset($catArr[0]);
        return backendView('create', ['catArr' => $catArr]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ArticleForm $result)
    {
        //
        try {

            $data = array(
                'title' => $result->input('title'),
                'user_id' => Auth::user()->id,
                'cate_id' => $result->input('cate_id'),
                'content' => $result->input('content'),
                'tags' => Tag::SetArticleTags($result->input('tags')),
                'pic' => Article::uploadImg('pic'),
            );

            if ($article = Article::create($data)) {
                if (ArticleStatus::initArticleStatus($article->id)) {
                    // 清除缓存
                    Cache::tags(Article::REDIS_ARTICLE_PAGE_TAG)->flush();
                    Notification::success('恭喜又写一篇文章');
                    return redirect()->route('backend.article.index');
                } else {
                    self::destroy($article->id);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
        return Article::select('content')->find($id)->content;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $catArr = Category::getCategoryTree();
        unset($catArr[0]);
        return backendView('edit', ['article' => Article::find($id), 'catArr' => $catArr]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(ArticleForm $result, $id)
    {
        //
        try {

            $data = array(
                'title' => $result->input('title'),
                'user_id' => Auth::user()->id,
                'cate_id' => $result->input('cate_id'),
                'content' => $result->input('content'),
                'tags' => Tag::SetArticleTags($result->input('tags')),
            );

            if (Request::hasFile('pic')) {
                $data['pic'] = Article::uploadImg('pic');
            }

            if (Article::where('id', $id)->update($data)) {
                Notification::success('更新成功');
                // 清除缓存
                Cache::tags(Article::REDIS_ARTICLE_PAGE_TAG)->flush();
                Cache::forget(Article::REDIS_ARTICLE_CACHE.$id);

                return redirect()->route('backend.article.index');
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $article = Article::find($id);
        if (!empty($article->pic)) {
            $fileName = public_path() . '/uploads/' . $article->pic;
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }

        if (ArticleStatus::deleteArticleStatus($id)) {

            if (Article::destroy($id)) {
                Notification::success('删除成功');
                Cache::tags(Article::REDIS_ARTICLE_PAGE_TAG)->flush();
                Cache::forget(Article::REDIS_ARTICLE_CACHE.$id);
            } else {
                Notification::error('主数据删除失败');
            }

        } else {
            Notification::error('动态删除失败');
        }

        return redirect()->route('backend.article.index');
    }

}
