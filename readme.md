## laravel-5-blog

Blog system development based on laravel  5.0.*

###Usage
---
1. clone laravel-5-blog 到你的服务器环境

	```
	cd www #你的服务器放网站的目录
	git clone git@github.com:yccphp/laravel-blog.git
	```

1. 切换到 laravel-5-blog 所在目录，使用composer 更新项目

	> 如果没有安装过composer请先安装：<br>
 	http://www.phpcomposer.com/
	```
	// 因为我提交的时候,为了避免大家重新下载各种包，我直接提交了 vendor ，所以执行 composer dump-autoload 就行
	cd laravel-5-blog/
	composer dump-autoload	
	```

1. 修改数据库配置`.env`,在数据库中创建一个`库`,把配置信息填写到配置文件中

1. 修改`app/storage/` 目录权限为可写,*nix下 执行：

    ```
    sudo chmod -R 755 app/storage/
    ```

1. 使用了默认的Auth ，请自行初始化
1. 安装数据库

    ```
    php artisan migrate #安装数据表结构
    ```

1. 开启重写模块:使用`apache`请开启`mod_rewrite`,使用`nginx`同学请参考这个配置示例：[https://gist.github.com/davzie/3938080](https://gist.github.com/davzie/3938080)


1. 把你的域名绑定到 `laravel-5-blog/public` 下

1. 那么现在访问`http://yourhost/backend` 应该会跳转到后台登录页。


###开发进度
---
目前还在开发中，您可以点击 `watch` ，订阅最新推送，可以点击 `start` 来支持我


后台开发进度

1. 后台登录：100%
2. 分类：100%
3. 标签：100%
4. 文章：100%
5. 网站基本设置：100%
6. 用户：100%
7. 导航管理：0%
8. 单页管理：0%
9. 评论：0%

前台开发进度
1. 首页：100%

喜欢这个项目，喜欢 laravel 欢迎加入 QQ 群与我们讨论：`365969825`

####感谢支持！
