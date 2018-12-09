## 安装
- [Laravel](#laravel)
- [Lumen](#lumen)

### Laravel

该软件包可用于 Laravel 5.6 或更高版本。

您可以通过 composer 安装软件包：

``` bash
composer require starrysea/validate
```

在 Laravel 5.6 中，服务提供商将自动注册。在旧版本的框架中，只需在 `config/app.php` 文件中添加服务提供程序：

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

```php
$app->register(Starrysea\Validate\ValidateServiceProvider::class); // 注册 Validate 服务提供者

class_alias(Starrysea\Validate\Validate::class, 'Validate'); // 添加 Validate 门面
```

## 用法

```php
// app/Providers/RouteServiceProvider.php

use Starrysea\Validate\Validate;

class RouteServiceProvider
{
    // ...
    
    public function boot()
    {
        Validate::RoutesParameter(); // 定义路由参数验证
        // ...
    }
    
    // ...
}
```

```php
// routes/web.php

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
// app/Providers/AppServiceProvider.php

use Starrysea\Validate\Validate;

class AppServiceProvider
{
    // ...

    public function boot()
    {
        Validate::FormRules(); // 扩展表单验证规则
        // ...
    }
    
    // ...
}
```

```php
// resources/lang/en/validation.php

return [
    // ...

    'identitys' => 'The :attribute not a valid id number.',

    'phone' => 'The :attribute not a legal chinese mobile number.',

    'english' => 'The :attribute contains characters other than spaces, english, and numbers.',

    'password' => 'The :attribute does not meet the password verification rules.',
];

// resources/lang/zh_cn/validation.php

return [
    // ...

    'identitys' => ':attribute 不是合法的身份证号码',

    'phone' => ':attribute 不是合法的中国手机号码',

    'english' => ':attribute 包含了空格、英文、数字以外的字符',

    'password' => ':attribute 不符合密码验证规则',
];
```

```php
// vendor/laravel/framework/src/Illuminate/Foundation/Http/FormRequest.php

use Starrysea\Validate\FormRequest as FormRequestDevelop;

class FormRequest
{
    // ...
    
    // 引入表单验证扩展
    use FormRequestDevelop;
    
    // 重写创建验证实例方法
    protected function createDefaultValidator(ValidationFactory $factory)
    {
        return $factory->make(
            $this->validationData(), $this->container->call([$this, 'combine']),
            $this->messages(), $this->attributes()
        );
    }
    
    // 重写获取验证数据方法
    public function validated()
    {
        $rules = $this->container->call([$this, 'combine']);

        return $this->only(collect($rules)->keys()->map(function ($rule) {
            return explode('.', $rule)[0];
        })->unique()->toArray());
    }

    // ...
}
```

```php
class FormRequestGatherTest
{
    // 展现构筑规则方法名
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

    // 构筑规则获得如下方法, 该规则仅 get 请求生效
    public function rulesGet()
    {
        return [
            'phone' => 'bail|required|phone',
            'password' => 'bail|required|password:c',
        ];
    }

    // 构筑规则获得如下方法, 该规则仅 admin/system/role 路径请求生效
    public function rulespathAdminSystemRole()
    {
        return [
            //
        ];
    }

    // 构筑规则获得如下方法, 该规则仅路由名 role 请求生效
    public function rulesrouteRole()
    {
        return [
            //
        ];
    }
}
```
