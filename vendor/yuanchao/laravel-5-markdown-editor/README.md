# laravel-5-markdown-editor
Based on the markdown editor laravel 5

一个基于 laravel 5 的markdown 编辑器

本项目基于 html&js 一个有情怀的编辑器二次开发完成 [传送门](https://github.com/Integ/BachEditor)


# 安装使用详细教程

phphub: [https://phphub.org/topics/853](https://phphub.org/topics/853)

如果访问 phphub 比较慢的同学，可以访问这个

SegmentFault:[http://segmentfault.com/a/1190000002780158](http://segmentfault.com/a/1190000002780158)

# 不需要敲语法可界面操作的功能
1. 加粗字体
2. 加斜字体
3. `无需手写 md插入链接`
4. 引用
5. `无需手写 md 语法插入图片`
6. 数字列表
7. 普通列表
8. 标题
9. 分割
10. 撤销
11. 重做
12. 全屏

# Bug 反馈&交流

欢迎加入我们的 laravel 学习小组：`365969825`

# 预览
<img src="http://www.phpcto.org/tmp/m1.jpg" width = "400" height = "200"  align=center />

<img src="http://www.phpcto.org/tmp/m2.jpg" width = "300" height = "200"  align=center />

# Update Log

`2015-05-18` 初版提交

`2015-05-19`  图片上传移植到扩展内部处理

`2015-05-19`  新增解析 markdown 为 html 功能

# Installation

1.在 `composer.json` 的 require里 加入

```
"yuanchao/laravel-5-markdown-editor": "dev-master"
```
2.执行 `composer update`

3.在config/app.php 的 `providers` 数组加入一条

```
'YuanChao\Editor\EndaEditorServiceProvider'
```

4.在config/app.php 的 `aliases` 数组加入一条

```
'EndaEditor' => 'YuanChao\Editor\Facade\EndaEditorFacade'

```

5.执行 `php artisan vendor:publish`

执行完上面的命令后，会生成配置文件和视图文件到你的 config/ 和 views/vendor 目录

# Usage 

1.在需要编辑器的地方插入以下代码

```
// 引入编辑器代码
@include('editor::head')

// 编辑器一定要被一个 class 为 editor 的容器包住
<div class="editor">
	// 创建一个 textarea 而已，具体的看手册，主要在于它的 id 为 myEditor
	{!! Form::textarea('content', '', ['class' => 'form-control','id'=>'myEditor']) !!}
	
	// 上面的 Form::textarea ，在laravel 5 中被提了出去，如果你没安装的话，直接这样用
	<textarea id='myEditor'></textarea>
	
	// 主要还是在容器的 ID 为 myEditor 就行
	
</div>

```

这个时候，编辑器就出来啦～

#### 图片上传移植到扩展内部处理

`图片上传移植到扩展的功能上传时间为 2015-05-19 10:40 如果在这个时间前安装的朋友，请先更新`

2.图片上传配置，打开config/editor.php 配置文件，修改里面的 `uploadUrl` 配置项，为你的处理上传的 action 

我的上传 action 代码为

```
use EndaEditor;

public function postUpload(){


		// endaEdit 为你 public 下的目录 update 2015-05-19
        $data = EndaEditor::uploadImgFile('endaEdit');

        return json_encode($data);            
}


```

###完成以上这些配置，你就可以在线插入图片啦


### 新增解析 markdown 为 html 功能

头部引用文件
```
use EndaEditor;

```

列子如下：
```

        $art = Article::find(16);


        return view('test',[
            'content'=>EndaEditor::MarkDecode($art->content)
        ]);
        
        
```

直接把需要解析的 markdown 扔进这个方法就行

```
EndaEditor::MarkDecode("#我是参数")

```
