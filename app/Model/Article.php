<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth,Input,Request,Cache;
use App\Model\Tag;
class Article extends Model {

	//
    const REDIS_NEW_ARTICLE_CACHE = 'redis_new_article_cache';
    
    protected $table = 'article';

    protected $fillable = [
        'cate_id',
        'user_id',
        'title',
        'content',
        'tags',
        'new_tags',
        'pic'
    ];

    public function status(){
        return $this->hasOne('App\Model\ArticleStatus','art_id');
    }

    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }

    public function category(){
        return $this->hasOne('App\Model\Category','id','cate_id');
    }

    /**
     * 范围查询
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function scopeUserId($query,$userId){
        return $query->where('user_id','=',$userId);
    }

    public static function setFieldData(){
        $fieldData = array();
        $article = new Article();
        $arr = $article->getFillable();
        foreach($arr as $v){
            $fieldData[$v] = Input::get($v);
        }
        $fieldData['user_id'] =  Auth::user()->id;
        $fieldData['tags'] =  Tag::SetArticleTags($fieldData['tags'],$fieldData['new_tags']);

        // 文件上传
        if (Request::hasFile('pic')){
            $pic = Request::file('pic');
            if($pic->isValid()){
                $newName = md5(rand(1,1000).$pic->getClientOriginalName()).".".$pic->getClientOriginalExtension();
                 $pic->move('uploads',$newName);
                $fieldData['pic'] = $newName;
            }
        }else{
            unset($fieldData['pic']);
        }



        unset($fieldData['new_tags']);
        unset($arr);
        unset($article);
        return $fieldData;
    }

    public static function getArticleModelByArticleId($articleId){
        return !empty($articleId)?self::find($articleId):null;
    }

    /**
     * 获取最新的文章
     * @param int $limit 条数
     * @return mixed
     */
    public static function getNewsArticle($limit=10){
        $model = self::orderBy('id','DESC')->simplePaginate($limit);
        return $model;
    }

    /**
     * 根据分类获取文章
     * @param $catId
     * @param int $limit
     * @return mixed
     */
    public static function getArticleListByCatId($catId,$limit=10){
        $model = self::where('cate_id',$catId)->orderBy('id','desc')->simplePaginate($limit);
        return $model;
    }

    /**
     * 根据作者获取文章
     * @param $userid
     * @param int $limit
     * @param bool $page
     * @return null
     */
    public static function getArticleModelByUserId($userid,$limit=3){
        $model = self::userId($userid)->orderBy('id','DESC')->simplePaginate($limit);
        return $model;
    }

    /**
     * 获取热门文章
     * @param int $limit
     * @param bool $page
     * @return mixed
     */
    public static function getHotArticle($limit=3){
        $select = [
            'article.id',
            'article.pic',
            'article.title',
            'article.created_at',
            'article_status.view_number',
        ];
        $model = self::select($select)->leftJoin('article_status','article.id','=','article_status.art_id')->orderBy('article_status.view_number','desc')->simplePaginate($limit);
        return $model;
    }

    /**
     * 关键字搜索
     * @todo 后期做成 Coreseek 分词搜索
     * @param $keyword
     * @return mixed
     */
    public static function getArticleListByKeyword($keyword){
        if(empty($keyword)){
            return null;
        }
        return self::where('title','like',"%$keyword%")->orderBy('id','desc')->simplePaginate(10);
    }

    public static function getArticleListByTagId($tagId){
        if(empty($tagId)){
            return null;
        }
        return self::whereRaw(
            'find_in_set(?, tags)',
            [$tagId] // bindings array
        )->orderBy('id','desc')->simplePaginate(10);
    }
}
