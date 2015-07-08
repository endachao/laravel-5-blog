<?php namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	public $table = 'comments';

    const ARTICLE_TYPE_ID = 0;
    const PAGE_TYPE_ID = 1;
    static $headerImg = [
        'min'=>1,
        'max'=>18,
    ];

    /**
     * username 用户名
     * email 邮箱
     * parent_id 上级id
     * el_id 对象id
     * type_id 0 为文章 1为单页
     * content 内容
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'parent_id',
        'el_id',
        'type_id',
        'content',
    ];

    public static function getCommentModelByCommentId($commentId){
        return self::find($commentId);
    }
    /**
     * 范围查询
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function scopeCommentWhere($query,$elId,$typeId){
        return $query->where('el_id','=',$elId)->where('type_id','=',$typeId);
    }

    /**
     * 获取评论列表
     * @param $elId
     * @param int $typeId
     * @param int $limit
     * @param bool $page
     * @return null
     */
    public static function getCommentListModel($elId,$typeId=self::ARTICLE_TYPE_ID,$limit=10,$page=true){
        $commentList = null;
        if(!empty($elId)){
            $model = self::CommentWhere($elId,$typeId)->orderBy('id','ASC');
            if($page){
                $commentList = $model->simplePaginate($limit);
            }else{
                $commentList = $model->limit($limit)->get();
            }
        }
        return $commentList;
    }

    /**
     * 获取评论头像
     * @return string
     */
    public static function getHeaderImg(){
        $img = rand(self::$headerImg['min'],self::$headerImg['max']).'.jpg';
        return homeAsset('/headimg/'.$img);
    }

    /**
     * 获取评论回复的用户名
     * @param $commentId
     * @return mixed|string
     */
    public static function getCommentReplyUserNameByCommentId($commentId){
        $commentModel = self::getCommentModelByCommentId($commentId);
        return isset($commentModel->username)?$commentModel->username:'';
    }

}
