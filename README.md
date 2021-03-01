# Tncrm 管理后台
Tncrm是一个简单的供应商 - 多经销商的CRM管理后台。基于`Laravel` 6.x 版本开发，以[dcat-admin](https://github.com/jqhph/dcat-admin)的多应用功能快速构建。

> 注：本项目尚未完成。

### 环境

 - PHP >= 7.1.0
 - Fileinfo PHP Extensio

 ### 安装

1，clone本项目到本地之后，进入到项目文件夹，执行安装命令
```
composer install
```

2，将项目根目录下的`.env.example`文件另存为`.env`文件

3，生成密钥
```
php artisan key:generate
```

4，新建数据库（如果使用`mysql`版本不低于5.7），将数据库信息配置到`.env`文件

5，迁移数据库文件
```
php artisan migrate
```

6，填充数据
```
php artisan db:seed --class=DataInitSeeder
```

7, 配置好虚拟主机，目录指向`./public`，至此安装完成。打开`http://域名/admin`访问供应商后台（主管理），打开`http://域名/dealer`访问经销商后台。
`Admin`登陆账号：admin, 密码：admin;

### License
------------
`tncrm` is licensed under [The MIT License (MIT)](LICENSE).