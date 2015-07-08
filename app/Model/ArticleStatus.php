<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ArticleStatus extends Model {

	//
    protected $table = 'article_status';

    public $timestamps = false;

    protected $fillable = [
        'art_id',
        'view_number',
        'comment_number'
    ];

    public function article(){
        return $this->hasOne('App\Model\Article','id','art_id');
    }

    public static function initArticleStatus($articleId){
        if(self::insert(array('art_id'=>$articleId))){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteArticleStatus($art_id){
        return self::where('art_id','=',$art_id)->first()->delete();
    }

    /**
     * 获取热门文章
     * @param int $limit
     * @param bool $page
     * @return mixed
     */
    public static function getHotArticle($limit=3,$page=true){
        $model = self::orderBy('view_number','DESC');
        if($page){
            $article = $model->simplePaginate($limit);
        }else{
            $article = $model->limit($limit)->get();
        }
        return $article;
    }

    /**
     * 更新到动态表
     * @param $artId
     * @return mixed
     */
    public static function updateCommentNumber($artId){
        return self::where('art_id','=',$artId)->increment('comment_number');
    }

}
