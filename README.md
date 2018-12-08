## 安装
- [Laravel](#laravel)
- [Lumen](#lumen)

### Laravel

该软件包可用于 Laravel 5.6 或更高版本。

您可以通过 composer 安装软件包：

``` bash
composer require starrysea/validate
```

在 Laravel 5.6 中，服务提供商将自动注册。在旧版本的框架中，只需在 config/app.php 文件中添加服务提供程序：

```php
'providers' => [
    // ...
    Starrysea\Validate\ValidateServiceProvider::class,
];

'aliases' => [
    // ...
    'Validate' => Starrysea\Validate\Validate::class,
];
```

### Lumen

您可以通过 composer 安装软件包：

``` bash
composer require starrysea/validate
```

注册服务提供者和门面：

```bash
$app->register(Starrysea\Validate\ValidateServiceProvider::class); // 注册 Validate 服务提供者

class_alias(Starrysea\Validate\Validate::class, 'Validate'); // 添加 Validate 门面
```

## 用法

```php
// app/Providers/RouteServiceProvider.php

use Starrysea\Validate\Validate;

class RouteServiceProvider
{
    ...
    
    public function boot()
    {
        Validate::RoutesParameter(); // 定义路由参数验证
        ...
    }
    
    ...
}
```

```php
// 验证 id/ids
Route::get('/{id}', function ($id) {
    return $id; // 纯数字通过, 否则 404 错误
});

// 验证手机号码
Route::get('/{phone}', function ($phone) {
    return $phone; // 中国11位手机号码通过, 否则 404 错误
});

// 验证多id, 以","号分隔, 如: 1,2,3,4,5
Route::get('/{manyid}', function ($manyid) {
    return $manyid; // 全部都为数字通过, 否则 404 错误
});
```

```php

```

```php
class FormRequestGatherTest
{
    // 展现构筑规则类名
    protected $showSource = true;

    // 展现当前请求验证的所有规则
    protected $showRule = true;

    // 该规则任何时候都生效, 但是优先级最低, 可被其它规则重写
    public function rules()
    {
        return [
            'idcode' => 'identitys',
            'phone'  => 'phone'
        ];
    }

    // 构筑规则获得如下类, 该规则仅 get 请求生效
    public function rulesGet()
    {
        return [
            'phone' => 'bail|required|phone',
            'password' => 'bail|required|password:c',
        ];
    }

    // 构筑规则获得如下类, 该规则仅 admin/system/role 路径请求生效
    public function rulespathAdminSystemRole()
    {
        return [
            //
        ];
    }

    // 构筑规则获得如下类, 该规则仅路由名 role 请求生效
    public function rulesrouteRole()
    {
        return [
            //
        ];
    }
}
```
