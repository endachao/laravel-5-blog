<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth, Input, Request, Cache;
use App\Model\Tag;

class Article extends Model
{

    //
    const REDIS_NEW_ARTICLE_CACHE = 'redis_new_article_cache_';

    const REDIS_ARTICLE_CACHE = 'redis_article_cache_';

    const REDIS_CATE_ARTICLE_CACHE = 'redis_cate_article_cache_';

    const REDIS_USER_ARTICLE_CACHE = 'redis_user_article_cache_';

    const REDIS_HOT_ARTICLE_CACHE = 'redis_hot_article_cache';

    const REDIS_SEARCH_ARTICLE_CACHE = 'redis_search_article_cache_';

    const REDIS_TAG_ARTICLE_CACHE = 'redis_tag_article_cache_';

    protected $table = 'article';

    static $cacheMinutes = 1440;

    protected $fillable = [
        'cate_id',
        'user_id',
        'title',
        'content',
        'tags',
        'new_tags',
        'pic'
    ];

    public function status()
    {
        return $this->hasOne('App\Model\ArticleStatus', 'art_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function category()
    {
        return $this->hasOne('App\Model\Category', 'id', 'cate_id');
    }

    /**
     * 范围查询
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function scopeUserId($query, $userId)
    {
        return $query->where('user_id', '=', $userId);
    }

    public static function setFieldData()
    {
        $fieldData = array();
        $article = new Article();
        $arr = $article->getFillable();
        foreach ($arr as $v) {
            $fieldData[$v] = Input::get($v);
        }
        $fieldData['user_id'] = Auth::user()->id;
        $fieldData['tags'] = Tag::SetArticleTags($fieldData['tags'], $fieldData['new_tags']);

        // 文件上传
        if (Request::hasFile('pic')) {
            $pic = Request::file('pic');
            if ($pic->isValid()) {
                $newName = md5(rand(1, 1000) . $pic->getClientOriginalName()) . "." . $pic->getClientOriginalExtension();
                $pic->move('uploads', $newName);
                $fieldData['pic'] = $newName;
            }
        } else {
            unset($fieldData['pic']);
        }


        unset($fieldData['new_tags']);
        unset($arr);
        unset($article);
        return $fieldData;
    }

    /**
     * 根据文章id获取文章
     * @param $articleId
     * @return \Illuminate\Support\Collection|null|static
     */
    public static function getArticleModelByArticleId($articleId)
    {
        if (empty($article = Cache::get(self::REDIS_ARTICLE_CACHE . $articleId))) {
            $article = self::find($articleId);
            Cache::add(self::REDIS_ARTICLE_CACHE . $articleId, $article, self::$cacheMinutes);
        }
        return $article;
    }

    /**
     * 获取最新的文章
     * @param int $limit 条数
     * @return mixed
     */
    public static function getNewsArticle($limit = 4)
    {

        $page = Input::get('page', 1);
        if (empty($model = Cache::get(self::REDIS_NEW_ARTICLE_CACHE . $page))) {
            $model = self::select('id')->orderBy('id', 'DESC')->simplePaginate($limit);
            Cache::add(self::REDIS_NEW_ARTICLE_CACHE . $page, $model, self::$cacheMinutes);
        }

        $articleList = array(
            'data' => [],
        );
        foreach ($model as $key => $article) {
            $articleList['data'][$key] = self::getArticleModelByArticleId($article->id);
        }
        $articleList['page'] = $model;
        return $articleList;
    }

    /**
     * 根据分类获取文章
     * @param $catId
     * @param int $limit
     * @return mixed
     */
    public static function getArticleListByCatId($catId, $limit = 10)
    {
        $page = Input::get('page', 1);

        $cacheName = $page . '_' . $catId;
        if (empty($model = Cache::get(self::REDIS_CATE_ARTICLE_CACHE . $cacheName))) {
            $model = self::select('id')->where('cate_id', $catId)->orderBy('id', 'desc')->simplePaginate($limit);
            Cache::add(self::REDIS_CATE_ARTICLE_CACHE . $cacheName, $model, self::$cacheMinutes);
        }

        $articleList = array(
            'data' => [],
        );
        foreach ($model as $key => $article) {
            $articleList['data'][$key] = self::getArticleModelByArticleId($article->id);
        }

        $articleList['page'] = $model;
        return $articleList;
    }

    /**
     * 根据作者获取文章
     * @param $userid
     * @param int $limit
     * @param bool $page
     * @return null
     */
    public static function getArticleModelByUserId($userId, $limit = 3)
    {
        if (empty($model = Cache::get(self::REDIS_USER_ARTICLE_CACHE . $userId))) {
            $model = self::select('id')->userId($userId)->orderBy('id', 'DESC')->limit($limit)->get();
            Cache::add(self::REDIS_USER_ARTICLE_CACHE . $userId, $model, self::$cacheMinutes);
        }
        $articleList = [];
        foreach ($model as $key => $article) {
            $articleList[$key] = self::getArticleModelByArticleId($article->id);
        }
        return $articleList;
    }

    /**
     * 获取热门文章
     * @param int $limit
     * @param bool $page
     * @return mixed
     */
    public static function getHotArticle($limit = 3)
    {
        if (empty($model = Cache::get(self::REDIS_HOT_ARTICLE_CACHE))) {
            $select = [
                'article.id',
                'article.pic',
                'article.title',
                'article.created_at',
                'article_status.view_number',
            ];
            $model = self::select($select)->leftJoin('article_status', 'article.id', '=', 'article_status.art_id')->orderBy('article_status.view_number', 'desc')->limit($limit)->get();
            Cache::add(self::REDIS_HOT_ARTICLE_CACHE, $model, self::$cacheMinutes);
        }

        return $model;
    }

    /**
     * 关键字搜索
     * @todo 后期做成 Coreseek 分词搜索
     * @param $keyword
     * @return mixed
     */
    public static function getArticleListByKeyword($keyword)
    {
        $page = Input::get('page', 1);
        $cacheName = $page . '_' . md5($keyword);

        if($model = empty(Cache::get(self::REDIS_SEARCH_ARTICLE_CACHE.$cacheName))){
            $model = self::select('id')->where('title', 'like', "%$keyword%")->orderBy('id', 'desc')->simplePaginate(10);
            Cache::add(self::REDIS_SEARCH_ARTICLE_CACHE.$cacheName, $model, self::$cacheMinutes);
        }

        $articleList = array(
            'data' => [],
        );
        foreach ($model as $key => $article) {
            $articleList['data'][$key] = self::getArticleModelByArticleId($article->id);
        }

        $articleList['page'] = $model;
        return $articleList;

    }

    public static function getArticleListByTagId($tagId)
    {
        if(empty($model = Cache::get(self::REDIS_TAG_ARTICLE_CACHE.$tagId))){
            $model = self::select('id')->whereRaw(
                'find_in_set(?, tags)',
                [$tagId]
            )->orderBy('id', 'desc')->simplePaginate(10);

            Cache::add(self::REDIS_TAG_ARTICLE_CACHE.$tagId, $model, self::$cacheMinutes);
        }

        $articleList = array(
            'data' => [],
        );
        foreach ($model as $key => $article) {
            $articleList['data'][$key] = self::getArticleModelByArticleId($article->id);
        }

        $articleList['page'] = $model;
        return $articleList;
    }
}
