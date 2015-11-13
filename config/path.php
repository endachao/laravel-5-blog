<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.03.17 下午1:56
 */
define('THEMES_NAME','themes');
define('CONTENT','content.');
define('SYSTEM','setting.');
define('USER','user.');
return [
    'backendBaseViewPath'=>'backend.',
    'modules'=>[
        'cate'=>CONTENT.'cate.',
        'article'=>CONTENT.'article.',
        'tags'=>CONTENT.'tags.',
        'system'=>SYSTEM.'system.',
        'navigation'=>SYSTEM.'navigation.',
        'links'=>SYSTEM.'links.',
        'user'=>USER,
        'comment'=>CONTENT.'comment.'
    ],
    'class'=>'',
];