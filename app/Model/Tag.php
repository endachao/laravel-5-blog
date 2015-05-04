<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Input;
class Tag extends Model {

	//
    protected $table = 'tags';

    protected $fillable = [
        'name',
        'number'
    ];

    public $timestamps = false;

    public static function getAllTagsArr(){

        return self::all();

    }

    public static function getAllTagsString(){
        $tags = self::getAllTagsArr();

        return self::getTagString($tags);

    }

    public static function getTagsNameByTagsIds($tagIds){
        $tags = explode(',',$tagIds);

        $tags = self::find($tags);

        return self::getTagString($tags);
    }

    public static function getTagString($result){

        $tag = "[";

        foreach($result as $k=>$v){
            $tag .= "'$v->name',";
        }

        $tag = trim($tag,',');

        $tag .= ']';

        return $tag;

    }

    public static function SetArticleTags($tags,$new_tags){
        $tagsArr = array();
        if(!empty($tags)){
            $tagsArr = explode(',',$tags);
        }

        $new_tagsArr = array();
        if(!empty($new_tags)){
            $new_tagsArr = explode(',',$new_tags);
        }

        $tag = array_merge($tagsArr,$new_tagsArr);

        $tagIds = array();
        if(!empty($tag)){
            foreach($tag as $K=>$v){
                $tag_temp = self::where('name','=',trim($v))->first();
                if($tag_temp){
                    $tag_temp->number += 1;
                    $tag_temp->save();
                    $tagIds[] = $tag_temp->id;
                }else{
                    // insert
                    $tagIds[] = self::insertGetId(['name'=>$v,'number'=>1]);
                }
            }

            unset($tag_temp);

        }

        return implode(',',$tagIds);

    }

    public static function setFieldData(){
        $fieldData = array();
        $tag = new Tag();
        $arr = $tag->getFillable();
        foreach($arr as $v){
            $fieldData[$v] = Input::get($v);
        }
        unset($arr);
        unset($fieldData['number']);
        unset($tag);
        return $fieldData;
    }

}
