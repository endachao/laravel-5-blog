<?php
/**
 * @author 袁超 <yccphp@163.com>
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    //
    protected $table;
    protected $fillable = [
        'sequence',
        'name',
        'url'
    ];

    /**
     * 获取链接列表
     * @param int $limit
     * @return mixed
     */
    public static function getLinkList($limit = 5)
    {
        return self::orderBy('sequence', 'asc')->limit($limit)->get();
    }


}
