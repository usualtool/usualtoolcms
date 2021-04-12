# UT框架（UsualToolCMS）

#### 介绍
UT框架又名UsualToolCMS，简称UTCMS。
是一款具备内容管理系统的快速建站框架。
使用模块、插件、模板引擎及行为控制器构建（M.P.T.C.）框架，适合快速开发各种类型的系统，且代码完全开源。框架拥有良好的生态，各种模块、插件、模板丰富多样，建站更加专业快速。

#### 环境
支持PHP5/PHP7/PHP8等已知向上发行的版本

开启MYSQLI扩展、CURL扩展、GD库

#### 权限
以下文件/目录需要开启写入/修改权限                                                                
文件:sql_db.php                                                                                   
目录:jsonapi,lang,modules,plugins,setup,templete,update 

#### 安装
在地址栏输入:http://localhost/setup/ 开始安装 

#### 开发及管理
开发者控制台:http://localhost/dev/ 对CMS进行部署和开发的可视平台                                  
内容管理平台:http://localhost/cms/ 开发者平台生成的CMS管理平台 

#### 系统结构

```
┌─assets      资源组
├─class        公共函数  
├─cms         CMS独立管理端
├─dev          开发者开发部署平台
├─home       前端交互
├─jsonapi      json接口
├─lang          语言包
├─modules    模块
├─plugins      扩展插件
├─setup         安装
├─templete    模板
│  ├─cache    缓存 
│  ├─skin       模板文件
├─update       更新包
└─其它文件
```


#### 静态
 **WIN IIS伪静态规则
使用方法：复制代码保存为web.config，放置在根目录下（IIS7及以上版本通过）** 

```
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
<system.webServer>
<rewrite>
<rules>
<rule name="UT规则 1">
<match url="^([a-zA-Z]+)\.html$" ignoreCase="false" />
<conditions logicalGrouping="MatchAll">
<add input="{QUERY_STRING}" pattern="^(.*)$" ignoreCase="false" />
</conditions>
<action type="Rewrite" url="index.php?ut={R:1}&amp;{C:1}" appendQueryString="false" />
</rule>
<rule name="UT规则 2">
<match url="^([a-zA-Z]+)-([0-9]+)\.html$" ignoreCase="false" />
<conditions logicalGrouping="MatchAll">
<add input="{QUERY_STRING}" pattern="^(.*)$" ignoreCase="false" />
</conditions>
<action type="Rewrite" url="index.php?ut={R:1}&amp;id={R:2}&amp;{C:1}" appendQueryString="false" />
</rule>
<rule name="UT规则 3">
<match url="^home/([a-zA-Z\-]+)\.html$" ignoreCase="false" />
<conditions logicalGrouping="MatchAll">
<add input="{QUERY_STRING}" pattern="^(.*)$" ignoreCase="false" />
</conditions>
<action type="Rewrite" url="home/index.php?ut={R:1}&amp;{C:1}" appendQueryString="false" />
</rule>
</rules>
</rewrite>
</system.webServer>
</configuration>
```

 **Apache伪静态规则
使用方法：复制代码保存为.htaccess，放置在根目录下** 

```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^([a-zA-Z]+)\.html$ index.php?ut=$1&%1
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^([a-zA-Z]+)-([0-9]+)\.html$ index.php?ut=$1&id=$2&%1
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^home/([a-zA-Z\-]+)\.html$ home/index.php?ut=$1&%1
</IfModule>
```

 **Nginx伪静态规则
使用方法：复制代码加入到nginx.conf配置文件server节点中** 

```
location / {
if (!-e $request_filename){
rewrite ^/([a-zA-Z]+)\.html$ /index.php?ut=$1 last;
rewrite ^/([a-zA-Z]+)-([0-9]+)\.html$ /index.php?ut=$1&id=$2 last;
rewrite ^/home/([a-zA-Z\-]+)\.html$ /home/index.php?ut=$1 last;
}
}
```

其他未明系统伪静态规则，请参照以上代码编写。

#### 相关开发文档
使用问答：http://b.usualtool.com                                                                                         
官方网站：http://cms.usualtool.com                                                                 
技术博客：http://www.usualtool.com/blog/                                                           
离线资源：http://gitee.com/usualtool/UsualToolCMS-8.0-Release/attach_files

使用手册：http://cms.usualtool.com/doc/                                                           
模块列表：http://cms.usualtool.com/mokuai.php                                                     
模板列表：http://cms.usualtool.com/pifu.php                                                       
插件列表：http://cms.usualtool.com/chajian.php                                                    
小程序列表：http://cms.usualtool.com/app.php