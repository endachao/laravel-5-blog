<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.05.18 上午10:33
 */
namespace YuanChao\Editor\Facade;
use Illuminate\Support\Facades\Facade;

class EndaEditorFacade extends Facade{
    protected static function getFacadeAccessor(){
        return 'EndaEditor';
    }
}