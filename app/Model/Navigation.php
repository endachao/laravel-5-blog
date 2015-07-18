<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{

    //
    protected $table = 'navigation';

    protected $fillable = [
        'parent_id',
        'sequence',
        'name',
        'url'
    ];

    static $navigation = [
        0 => '顶级导航'
    ];

    /**
     * 方便以后扩展
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getNavigationAll()
    {
        return tree(self::orderBy('sequence', 'asc')->get());
    }

    /**
     * 获取所有导航
     * @return array
     */
    public static function getNavigationArray()
    {
        if (empty(self::$navigation)) {
            $model = self::getNavigationAll();

            if (!empty($model)) {
                foreach ($model as $nav) {
                    self::$navigation[$nav->id] = $nav->html . $nav->name;
                }
            }
        }
        return self::$navigation;
    }

    /**
     * 获得导航名称
     * @param $id
     * @return string
     */
    public static function getNavNameByNavId($id)
    {
        if (!isset(self::$navigation[$id])) {
            $model = self::find($id);
            if (!empty($model)) {
                self::$navigation[$id] = $model->name;
            }
        }
        return isset(self::$navigation[$id]) ? self::$navigation[$id] : '';
    }

    /**
     * 获取子导航
     * @param $id
     * @return mixed
     */
    public static function getChildNav($id)
    {
        return self::where('parent_id', $id)->get();
    }


    /**
     * 是否包含子级
     * @param $id
     * @return bool
     */
    public static function isChildNav($id)
    {
        $child = self::where('parent_id', '=', $id)->first();
        return !empty($child) ? true : false;
    }

}
