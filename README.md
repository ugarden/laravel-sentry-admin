## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)


## Author:hc
## 邮箱：bomb.huang@qq.com 

## laravel-sentry-admin
本项目是一个基于laravel5.1开发的后台管理系统，集成sentry权限管理，前端使用H-ui.admin，还集成了验证码、七牛、二维码生成、百度富文本编辑器等众TabTab多组件，后面会陆续将组件demo发布。目前先上线权限管理，方便大家即取即用。sentry文档地址https://cartalyst.com/manual/sentry/2.1 当然有的看国TabTab外的不太理解，这里推荐一个中文文档由 @袁超 编写，感觉挺不错的，链接https://yccphp.gitbooks.io/sentry-manual-chinese-version/content/ 。

## 安装配置
1.这里提供了vender扩展，下载觉得文件过大的话可以省略此文件，此处sentry等第三方扩展已经配置好，只需执行composer update安装相关扩展。  
2.将数据库从database/目录下考出导入自己数据库，数据库有初始数据，千万不可删除，否则项目将不可行。【文件名laravel_sentry_admin.sql】，初始账  号：admin@qq.com 密码123456  
3.数据库相关配置在.env文件中，将配置更改为自己数据库配置即可。  
4.项目默认是debug模式，方便开发及时捕获错误信息，若放正式环境请将.env配置中的APP_DEBUG改为false。  
5.访问地址配置：默认是http://域名或者ip地址/项目名称/public/index.php/admin/login ,我的本地示例http://localhost/php/laravel-sentry-   admin/public/index.php/admin 这里可以在apache或者nginx中通过配置出现不同的地址展示形式。
6.laravel所需环境要求以及访问url相关配置请参考https://docs.golaravel.com/docs/5.1/installation/ 不理解的地方请发邮件或者issue，大家共同学习，谢谢。

## 项目图片示例
![github](https://raw.githubusercontent.com/ugarden/laravel-sentry-admin/master/public/images/login.png "github")  
![github](https://raw.githubusercontent.com/ugarden/laravel-sentry-admin/master/public/images/user.png "github")  
![github](https://raw.githubusercontent.com/ugarden/laravel-sentry-admin/master/public/images/role.png "github")  
![github](https://raw.githubusercontent.com/ugarden/laravel-sentry-admin/master/public/images/permission.png "github")  

## 致谢
在此致谢感谢H-ui、jQuery、layer、laypage、Validform、UEditor、My97DatePicker、iconfont、Datatables、WebUploaded、icheck、bootstrap-Switch
