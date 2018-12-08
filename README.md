## 安装
- [Laravel](#laravel)
- [Lumen](#lumen)

### Laravel

该软件包可用于 Laravel 5.6 或更高版本。

您可以通过 composer 安装软件包：

``` bash
composer require starrysea/curl
```

在 Laravel 5.6 中，服务提供商将自动注册。在旧版本的框架中，只需在 config/app.php 文件中添加服务提供程序：

```php
'providers' => [
    // ...
    Starrysea\Curl\CurlServiceProvider::class,
];

'aliases' => [
    // ...
    'Curl' => Starrysea\Curl\Curl::class,
];
```

### Lumen

您可以通过 composer 安装软件包：

``` bash
composer require starrysea/curl
```

注册服务提供者和门面：

```bash
$app->register(Starrysea\Curl\CurlServiceProvider::class); // 注册 Curl 服务提供者

class_alias(Starrysea\Curl\Curl::class, 'Curl'); // 添加 Curl 门面
```

## 用法

```php
use Starrysea\Curl\Curl;

class CurlGatherTest
{
    public static function get_curl()
    {
        return Curl::get_curl('https://github.com/caixingyue/laravel-starrysea-curl'); // get request

//        return Curl::get_curl('https://github.com/caixingyue/laravel-starrysea-curl', [
//            'title' => '你好, Laravel'
//        ]); // post request

//        return Curl::get_curl('https://github.com/caixingyue/laravel-starrysea-curl', [
//            'title' => '你好, Laravel'
//        ],[
//            'headers' => '星月来啦'
//        ]); // post and header request
    }

    public static function first()
    {
        return Curl::first()->get('https://github.com/caixingyue/laravel-starrysea-curl', [
            'title' => '你好, Laravel'
        ])->request(); // get request

//        return Curl::first()->post('https://github.com/caixingyue/laravel-starrysea-curl', [
//            'title' => '你好, Laravel'
//        ])->request(); // post request

//        return Curl::first()->get('https://github.com/caixingyue/laravel-starrysea-curl')->headers([
//            'title' => '你好, Laravel'
//        ])->request(); // get and header request
    }
}
```
