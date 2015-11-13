<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{

    //
    protected $table = 'navigation';

    public $child;

    protected $fillable = [
        'sequence',
        'name',
        'url'
    ];

    static $navigation = [];

    public static function getNavigationAll($limit = 5)
    {
        return self::orderBy('sequence', 'asc')->limit($limit)->get();
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
}
