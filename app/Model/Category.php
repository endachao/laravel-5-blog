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




    /**
     * 获取分类列表
     * @todo 排序需要修改
     * @return mixed
     */
    public static function getCategoryDataModel(){
        $category = self::all();

        return $category;
    }

    public static function getCatFieldData($catId=false){
        $category = Category::select('id','cate_name')->get();

        foreach($category as $k=>$v){
            self::$catData[$v->id] = $v->cate_name;
        }

        if($catId){
            unset(self::$catData[$catId]);
        }

        unset($category);

        return self::$catData;

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
}
