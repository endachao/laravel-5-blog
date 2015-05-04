<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.03.17 下午1:56
 */
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
        'user'=>USER,
    ],
    'class'=>'',
];