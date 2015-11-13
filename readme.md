## laravel-5.1-blog

Blog system development based on laravel  5.1.*

# Demo

演示地址：[http://www.phpyc.com/](http://www.phpyc.com/)

# Warning

必须安装 `redis`
必须安装 `redis`
必须安装 `redis`

重要的事情说三遍

# 学习交流

1. 可以加入 QQ 群 `365969825`

### 更新日志

1. 2015.8.8 修改文章字体［样式］
2. 2015.8.8 修改后台评论列表按钮问题［样式］
3. 2015.8.8 新增回复评论邮件通知功能［功能］
4. 2015.9.4 修改评论为DISQUS
5. 2015.11.12 更新版本为5.1
6. 2015.11.13 新的主题 完成

### 2.0 开发日志

1. 修改框架版本为 5.1 	［已完成］
2. 新的主题				［已完成］
4. 友情链接管理			［已完成］
5. 新增搜索关键字统计		［计划中］
6. 新增文章版权申明		［计划中］
7. 代码优化				［计划中］


### 升级为 2.0

从 github 更新后，执行一下 `composer dump-autoload`,然后执行 `php artisan migrate` 

###Usage
---
1. clone laravel-5-blog 到你的服务器环境

	```
	cd www #你的服务器放网站的目录
	git clone git@github.com:yccphp/laravel-5-blog.git
	```

1. 切换到 laravel-5-blog 所在目录，使用composer 更新项目

	> 如果没有安装过composer请先安装：<br>
 	http://www.phpcomposer.com/
	```
	// 因为我提交的时候,为了避免大家重新下载各种包，我直接提交了 vendor ，所以执行 composer dump-autoload 就行
	cd laravel-5-blog/
	composer dump-autoload	
	```

1. 修改 `.env.example` 为 `.env` 

1. 修改数据库配置`.env`,在数据库中创建一个`库`,把配置信息填写到配置文件中

1. 修改`storage/` 目录权限为可写,*nix下 执行：

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




### 安装评论

9.4 号，修改评论为国外第三方的DISQUS，这个评论还是挺好用的，使用方法如下

1. 先注册账户 https://disqus.com/ 得到你的站点 id
2. 修改配置文件 `config/disqus.php` 里面的 `disqus_shortname` 配置项为你刚得到的 id
3. 安装完成
4. 如果你是在 9.4 号之前下载安装的，需要执行一下 `php artisan migrate` 清理一下数据库，新安装的不用理会



###缓存
---

本系统使用redis缓存，目前只缓存文章，其它的皆不缓存


喜欢这个项目，喜欢 laravel 欢迎加入 QQ 群与我们讨论：`365969825`

####感谢支持！
