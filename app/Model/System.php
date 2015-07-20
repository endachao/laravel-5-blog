<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth, Input;

class System extends Model
{

    const SYSTEM_INFO_TYPE = 1;
    //
    protected $table = 'systems';

    public $timestamps = false;

    protected $fillable = array(
        'cate',
        'system_name',
        'system_value'
    );

    static $cate = [
        self::SYSTEM_INFO_TYPE => '基本设置',
    ];

    /**
     * 获取指定配置值
     * @param $field
     * @return mixed
     */
    public function getSystem($field)
    {
        return self::select('system_value')->where('system_name', $field)->pluck('system_value');
    }

}
