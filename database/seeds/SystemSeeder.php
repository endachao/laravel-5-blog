<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.04.06 下午4:31
 */
use Illuminate\Database\Seeder;
class SystemSeeder extends Seeder{

    public function run(){
        $config = [
            [
                'cate'=>1,
                'system_name'=>'title',
                'system_value'=>'网站标题'
            ],
            [
                'cate'=>1,
                'system_name'=>'subheading',
                'system_value'=>'副标题'
            ],
            [
                'cate'=>1,
                'system_name'=>'put_on_record',
                'system_value'=>'国安 00009'
            ],
            [
                'cate'=>1,
                'system_name'=>'all_ow_access',
                'system_value'=>1
            ],
            [
                'cate'=>1,
                'system_name'=>'allow_comments',
                'system_value'=>1
            ],
            [
                'cate'=>1,
                'system_name'=>'seo_key',
                'system_value'=>'seo 关键字'
            ],
            [
                'cate'=>1,
                'system_name'=>'seo_desc',
                'system_value'=>'seo 描述'
            ],
            [
                'cate'=>1,
                'system_name'=>'copyright',
                'system_value'=>'版权申明'
            ]
        ];
        DB::table('systems')->insert($config);
    }

}