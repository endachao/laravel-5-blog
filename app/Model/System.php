<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth,Input;
class System extends Model {

    const SYSTEM_INFO_TYPE = 1;
    const SYSTEM_SMTP_TYPE = 2;
	//
    protected $table = 'systems';

    public  $timestamps = false;

    protected $fillable = array(
        'cate',
        'system_name',
        'system_value'
    );

    static $cate = [
        self::SYSTEM_INFO_TYPE=>'基本设置',
        self::SYSTEM_SMTP_TYPE=>'邮箱设置',
    ];

    public static function setFieldData(){
        $fieldData = array();
        $system = new System();
        $arr = $system->getFillable();
        foreach($arr as $v){
            $fieldData[$v] = Input::get($v);
        }

        unset($arr);
        unset($system);
        return $fieldData;
    }

}
