<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{

    //
    protected $table = 'navigation';

    public $child;

    protected $fillable = [
        'parent_id',
        'sequence',
        'name',
        'url'
    ];

    static $navigation = [
        0 => '顶级导航'
    ];

    public static function getNavigationAll()
    {
        return self::orderBy('sequence', 'asc')->get();
    }

    /**
     * 方便以后扩展
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getTreeNavigationAll()
    {
        return tree(self::getNavigationAll());
    }

    /**
     * 获取所有导航
     * @return array
     */
    public static function getNavigationArray()
    {
        if (empty(self::$navigation)) {
            $model = self::getTreeNavigationAll();

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

    public static function getNavList()
    {
        $model = self::getNavigationAll();
        $data = [];
        if (!empty($model)) {
            foreach ($model as $key => $nav) {
                if($nav->parent_id == 0){
                    $data[$key] = $nav;
                    foreach ($model as $navigation) {
                        if ($navigation->parent_id == $nav->id) {
                            $data[$key]->child[] = $navigation;
                        }
                    }
                }
            }
        }
        return $data;
    }
}
