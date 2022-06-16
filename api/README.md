## 环境

- php   7.4
- composer	2.3.6
- ThinkPHP   5.1
- 部署环境	Linux：Centos7
- 使用宝塔面板部署LNMP环境
- 解禁putenv函数，以便使用composer来安装插件

请将项目文件放到Nginx或Apache网站根目录下，并且修改对应的文件权限其归属（750）

请将public目录及runtime目录给予777权限，以便正常访问公共文件并安装插件

请自行配置文件根目录.env文件，以便连接对应数据库

```.env
DATABASE_TYPE = mysql					
DATABASE_HOST = localhost			
DATABASE_PORT = 3306
DATABASE_NAME = 			#数据库名称
DATABASE_USERNAME = 		        #数据库用户名
DATABASE_PASSWORD = 		        #数据库密码
LOCAL_URL = 			        #本地部署地址
TOKEN_KEY =				#token密匙
```

导入数据库文件
php-bgmanage.sql


composer 安装对应组件，请自行配置镜像源
```shell
composer install				#安装对应的组件
```