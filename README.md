# 进销存管理系统

初始化文件数据
```bash
php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
```
初始化数据表
```bash
php artisan admin:install
```
### 初始化工作流程

初始化数据库表
```bash
php artisan make:model Models/Products -m
```

创建数据库
```bash
php artisan migrate
```

初始化控制器
```bash
 php artisan admin:make ProductController --model=App\\Models\\Products
```
