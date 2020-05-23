<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.03.18 下午4:08
 */

if (!function_exists('backendView')) {
    /**
     * 展示后台view
     * @author 袁超
     * @param  string $view
     * @param  array $data
     * @param  array $mergeData
     * @return \Illuminate\View\View
     */
    function backendView($view = null, $data = array(), $mergeData = array())
    {
        $factory = app('Illuminate\Contracts\View\Factory');
        if (func_num_args() === 0) {
            return $factory;
        }
        $BaseviewPath = Config::get('path.backendBaseViewPath');
        $module = Config::get('path.class');
        if (!empty($module)) {
            $BaseviewPath .= Config::get('path.modules.' . $module);
            Config::set('path.class', '');
        }
        return $factory->make($BaseviewPath . $view, $data, $mergeData);
    }
}
if (!function_exists('conversionClassPath')) {
    /**
     * 转换class 名
     * @author 袁超
     * @param  string $className
     * @return string
     */
    function conversionClassPath($className)
    {
        $className = str_replace('\\', '-', $className);
        if (preg_match("/.*-(.*)Controller/is", $className, $matches)) {
            Config::set('path.class', strtolower($matches[1]));
        } else {
            return response('conversionClassPathError', 500);
        }
    }
}
if (!function_exists('homeView')) {
    /**
     * 展示前台view
     * @author 袁超
     * @param  string $view
     * @param  array $data
     * @param  array $mergeData
     * @return \Illuminate\View\View
     */
    function homeView($view = null, $data = array(), $mergeData = array())
    {
        $factory = app('Illuminate\Contracts\View\Factory');
        if (func_num_args() === 0) {
            return $factory;
        }
        $themes = THEMES_NAME . '.' . Config::get('app.themes');
        return $factory->make($themes . '.' . $view, $data, $mergeData);
    }
}
if (!function_exists('homeAsset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    function homeAsset($path, $secure = null)
    {
        $themes = THEMES_NAME . DIRECTORY_SEPARATOR . Config::get('app.themes');
        return app('url')->asset($themes . $path, $secure);
    }
}

if (!function_exists('strCut')) {
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

if (!function_exists('viewInit')) {
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
        $tags = app('App\Model\Tag');
        $view = app('view');
        $nav = app('App\Model\Navigation');
        $links = app('App\Model\Links');


        $view->share('hotArticleList', $article::getHotArticle(3));
        $view->share('tagList', $tags::getHotTags(12));
        $view->share('navList', $nav::getNavigationAll());
        $view->share('linkList', $links::getLinkList());
    }
}

if (!function_exists('conversionMarkdown')) {
    /**
     * @param $markdownContent
     * @return string
     */
    function conversionMarkdown($markdownContent)
    {
        $endaEditor = app('YuanChao\Editor\Facade\EndaEditorFacade');
        return !empty($markdownContent) ? $endaEditor::MarkDecode($markdownContent) : '';
    }
}

if (!function_exists('uploadFile')) {
    /**
     * @param $type
     * @param $field
     * @param $path
     * @return bool|string
     */
    function uploadFile($type, $field, $path)
    {
        $allowType = array(
            'img' => array(
                'image/gif',
                'image/ief',
                'image/jpeg',
                'image/png',
                'image/tiff',
                'image/x-ms-bmp',
            )
        );
        $url = '';
        if (!isset($allowType[$type])) {
            return false;
        }
        $request = app('Request');
        if ($request::hasFile($field)) {
            $pic = $request::file($field);
            if (in_array($pic->getMimeType(), $allowType[$type])) {
                if ($pic->isValid()) {
                    $newName = md5(rand(1, 1000) . $pic->getClientOriginalName()) . "." . $pic->getClientOriginalExtension();
                    $pic->move($path, $newName);
                    $url = $newName;
                }
            }
        }
        return $url;
    }
}

if (!function_exists('tree')) {
    //重新写分类树
    function tree($model, $parentId = 0, $level = 0, $html = '-')
    {
        static $list = array();
        foreach ($model as $k => $v) {
            if ($v->parent_id == $parentId) {
                if ($level != 0) {
                    $v->html = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
                    $v->html .= '|';
                }
                $v->html .= str_repeat($html, $level);
                $list[] = $v;
                tree($model, $v->id, $level + 1);
            }
        }

        return $list;
    }
}

/**
 * 获取系统设置
 */
if (!function_exists('systemConfig')) {
    function systemConfig($field, $default = '')
    {
        $system = app('App\Model\System');
        $val = $system->getSystem($field);
        return !empty($val) ? $val : $default;
    }
}

if (!function_exists('getArticleImg')) {
    function getArticleImg($image = '')
    {
        $imageUrl = 'images/01.jpg';
        if (!empty($image)) {
            $imageUrl = 'uploads' . '/' . $image;
        }
        return asset($imageUrl);
    }
}
