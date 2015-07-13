<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth,Input,Request;
use App\Model\Tag;
class Article extends Model {

	//
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
     * @param bool $page 是否分页
     * @return mixed
     */
    public static function getNewsArticle($catId=0,$limit=4,$page=true){
        $model = self::orderBy('id','DESC');
        if($catId > 0){
            $model = $model->where('cate_id','=',$catId);
        }
        if($page){
            $article = $model->simplePaginate($limit);
        }else{
            $article = $model->limit($limit)->get();
        }
        return $article;
    }

    /**
     * 根据作者获取文章
     * @param $userid
     * @param int $limit
     * @param bool $page
     * @return null
     */
    public static function getArticleModelByUserId($userid,$limit=3,$page=false){
        $article = null;
        if(!empty($userid)){
            $model = self::userId($userid)->orderBy('id','DESC');
            if($page){
                $article = $model->simplePaginate($limit);
            }else{
                $article = $model->limit($limit)->get();
            }
        }
        return $article;
    }
}
