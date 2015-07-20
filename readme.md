## laravel-5-blog

Blog system development based on laravel  5.0.*

# Demo

演示地址：[http://www.phpyc.com/](http://www.phpyc.com/)

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
    sudo chmod -R 755 storage/
    ```

1. 修改`public/uploads` 目录权限为可写,*nix下 执行：

    ```
    sudo chmod -R 755 public/uploads/

    ```


1. 安装数据库

    ```
    php artisan migrate #安装数据表结构
    ```

1. 填充数据

	```
		php artisan db:seed
	```


1. 开启重写模块:使用`apache`请开启`mod_rewrite`,使用`nginx`同学请参考这个配置示例：[https://gist.github.com/davzie/3938080](https://gist.github.com/davzie/3938080)


1. 把你的域名绑定到 `laravel-5-blog/public` 下

1. 那么现在访问`http://yourhost/backend` 应该会跳转到后台登录页，默认账户：`admin@admin.com`,`123456`


###开发进度
---
目前基本上开发完成，后期都是代码优化系列，您可以点击 `watch` ，订阅最新推送，可以点击 `start` 来支持我


###缓存
---

本系统使用redis缓存，目前只缓存文章，其它的皆不缓存




喜欢这个项目，喜欢 laravel 欢迎加入 QQ 群与我们讨论：`365969825`

####感谢支持！
