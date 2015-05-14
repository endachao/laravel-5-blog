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
if ( ! function_exists('homeView'))
{
    /**
     * 展示前台view
     * @author 袁超
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View
     */
    function homeView($view = null, $data = array(), $mergeData = array())
    {
        $factory = app('Illuminate\Contracts\View\Factory');
        if (func_num_args() === 0)
        {
            return $factory;
        }
        $themes = THEMES_NAME.'.'.Config::get('app.themes');
        return $factory->make($themes.'.'.$view, $data, $mergeData);
    }
}
if ( ! function_exists('homeAsset'))
{
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool    $secure
     * @return string
     */
    function homeAsset($path, $secure = null)
    {
        $themes = THEMES_NAME.DIRECTORY_SEPARATOR.Config::get('app.themes');
        return app('url')->asset($themes.$path, $secure);
    }
}

if ( ! function_exists('strCut'))
{
    /**
     * 字符串截取
     * @param string $string
     * @param integer $length
     * @param string $suffix
     * @return string
     */
    function strCut($string, $length, $suffix = '...')
    {
        $resultString = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strLength = strlen($string);
        for ($i = 0; (($i < $strLength) && ($length > 0)); $i++) {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
                if ($length < 1.0) {
                    break;
                }
                $resultString .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            } else {
                $resultString .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $resultString = htmlspecialchars($resultString, ENT_QUOTES, 'UTF-8');
        if ($i < $strLength) {
            $resultString .= $suffix;
        }
        return $resultString;
    }
}

if ( ! function_exists('viewInit'))
{
    /**
     * 字符串截取
     * @param string $string
     * @param integer $length
     * @param string $suffix
     * @return string
     */
    function viewInit()
    {
        $article = app('App\Model\Article');
        $articleStatus = app('App\Model\ArticleStatus');
        $tags = app('App\Model\Tag');
        $view = app('view');

        $count = array(
            'article'=>$article->count(),
            'comment'=>$articleStatus->sum('comment_number'),
            'visit'=>$articleStatus->sum('view_number'),
        );

        $view->share('recentArticle', $article::getNewsArticle(3,false));
        $view->share('hotTags', $tags::getHotTags(12));
        $view->share('dataCount', $count);
    }
}
