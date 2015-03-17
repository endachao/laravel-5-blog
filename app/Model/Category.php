<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * 获取分类列表
     * @todo 排序需要修改
     * @return mixed
     */
    public static function getCategoryDataModel(){
        $category = self::all();

        return $category;
    }

}
