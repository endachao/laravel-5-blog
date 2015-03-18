<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.03.18 下午4:08
 */

if ( ! function_exists('backendView'))
{
    /**
     * 展示后台view
     * @author 袁超
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View
     */
    function backendView($view = null, $data = array(), $mergeData = array())
    {
        $factory = app('Illuminate\Contracts\View\Factory');
        if (func_num_args() === 0)
        {
            return $factory;
        }
        $BaseviewPath = Config::get('path.backendBaseViewPath');
        $module = Config::get('path.class');
        if(!empty($module)){
            $BaseviewPath .= Config::get('path.modules.'.$module);
            Config::set('path.class','');
        }
        return $factory->make($BaseviewPath.$view, $data, $mergeData);
    }
}
if ( ! function_exists('conversionClassPath'))
{
    /**
     * 转换class 名
     * @author 袁超
     * @param  string  $className
     * @return string
     */
    function conversionClassPath($className)
    {
        $className = str_replace('\\','-',$className);
        if(preg_match("/.*-(.*)Controller/is",$className,$matches)){
            Config::set('path.class',strtolower($matches[1]));
        }else{
            return response('conversionClassPathError', 500);
        }
    }
}
