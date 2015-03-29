<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth,Input;
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
    ];

    public function status(){
        return $this->hasOne('App\Model\ArticleStatus','art_id');
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

        unset($fieldData['new_tags']);
        unset($arr);
        unset($category);
        return $fieldData;
    }
}
