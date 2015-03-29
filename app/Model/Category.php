<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Input;
class Category extends Model {

	//
    protected $table = 'category';


    protected $fillable = [
        'cate_name',
        'as_name',
        'parent_id',
        'seo_title',
        'seo_key',
        'seo_desc',
    ];

    static $catData = [
        0=>'顶级分类',
    ];

    static $category = [];


    public $html;

    /**
     * 获取分类列表
     * @return mixed
     */
    public static function getCategoryDataModel(){
        $category = self::all();

        $data = self::getSortModel($category);

        return $data;
    }

    /**
     * 此方法维护静态数组 $category
     */
    private  static function getCategoryArr($catId){

        if(!isset(self::$category[$catId])){
            $cate = self::select('cate_name')->find($catId)->toArray();
            if(empty($cate)){
                return false;
            }
            self::$category[$catId] = $cate['cate_name'];
        }

        return self::$category[$catId];

    }

    public static function getCategoryNameByCatId($catId){

        $cate = self::getCategoryArr($catId);

        return !empty($cate)?$cate:'分类不存在';
    }

    public static function getCatFieldData($catId=false){

        $data = self::getSortModel(Category::select('id','cate_name','parent_id')->get());

        foreach($data as $k=>$v){
            self::$catData[$v->id] = $v->html.$v->cate_name;
        }

        if($catId){
            unset(self::$catData[$catId]);
        }

        unset($category);

        return self::$catData;

    }

    public static function getTreeCatArr($model){

    }

    public static function setFieldData(){
        $fieldData = array();
        $category = new Category();
        $arr = $category->getFillable();
        foreach($arr as $v){
            $fieldData[$v] = Input::get($v);
        }
        unset($arr);
        unset($category);
        return $fieldData;
    }


    public static function getSortModel($model,$parentId=0,$level=0,$html='-'){

        $data = array();
        foreach($model as $k=>$v){

            if($v->parent_id == $parentId){

                if($level != 0){
                    $v->html = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$level);
                    $v->html .= '|';

                }

                $v->html .= str_repeat($html,$level);

                $data[] = $v;

                $data = array_merge($data,self::getSortModel($model,$v->id,$level+1));
            }

        }

        return $data;

    }
}
