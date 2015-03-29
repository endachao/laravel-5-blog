<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Input,Redirect,Notification;
use App\Model\Article;
use App\Model\Category;
use App\Model\ArticleStatus;
use App\Http\Requests\ArticleForm;
class ArticleController extends Controller {

    public function __construct(){
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
        return backendView('index',['article'=>Article::orderBy('id','DESC')->paginate(10)]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        $catArr = Category::getCatFieldData();
        unset($catArr[0]);
        return backendView('create',['catArr'=>$catArr]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ArticleForm $result)
	{
		//
        try{
            if($article = Article::create(Article::setFieldData())){
                if(ArticleStatus::initArticleStatus($article->id)){
                    Notification::success('恭喜又写一篇文章');
                    return Redirect::route('backend.article.index');
                }else{
                    self::destroy($article->id);
                }

            }
        }catch (\Exception $e){
            return Redirect::back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }
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
        return Article::select('content')->find($id)->content;
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
        $catArr = Category::getCatFieldData();
        unset($catArr[0]);
        return backendView('edit',['article'=>Article::find($id),'catArr'=>$catArr]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(ArticleForm $result,$id)
	{
		//
        try{

            if(Article::where('id',$id)->update(Article::setFieldData())){

                Notification::success('更新成功');

                return Redirect::route('backend.article.index');
            }

        }catch (\Exception $e){
            return Redirect::back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }
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
        if(ArticleStatus::deleteArticleStatus($id)){

            if(Article::destroy($id)){
                Notification::success('删除成功');
                return Redirect::route('backend.article.index');
            }else{
                Notification::error('主数据删除失败');
            }

        }else{
            Notification::error('动态删除失败');
        }

        return Redirect::route('backend.article.index');
	}

}
