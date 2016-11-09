# web_phalcon
[![Author](https://img.shields.io/badge/author-@weibo-blue.svg?style=flat)](http://weibo.com/jzhbiao) 

##Overview
一个对phalcon框架实现的例子，直接clone，业务逻辑在写在mvc上，并保存一些我代码，欢迎大家fock

##Feature

### 1.Install
- 安装phalcon（请安装php7的phalcon，我的sina博客有写），或者到官网看详细安装过程，有问题也可以留言我
- 安装wamp windows 一键安装环境，很方便，建议去官网下载。服务器简单的可以是：centos7+nginx+mariadb+php7+phalcon
- 本例用win wamp, git clone项目到你的wamp www目录

### 2.Configuration
- 到D:\mapp\wamp64\bin\apache\apache2.4.18\conf\httpd.conf 取消mod_rewrite，httpd-vhosts.conf的注释
- php.ini 增加扩展请注意 apache文件下的php.ini
- 下载composer 安装php 相关的包
- vhosts配置，主意host 加上 web_phalcon
```
<VirtualHost *:80>
	ServerName web_phalcon
	DocumentRoot D:/www/web_phalcon/public
	<Directory  "D:/www/web_phalcon/public">
		Options +Indexes +FollowSymLinks +MultiViews
		AllowOverride All
		Require local
	</Directory>
</VirtualHost>

```