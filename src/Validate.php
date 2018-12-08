<?php

namespace Starrysea\Validate;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Starrysea\Arrays\Arrays;

class Validate
{
    // 路由参数验证
    public static function RoutesParameter()
    {
        // 验证中国手机号码
        Route::pattern('phone', '1[0-9]\d{9}');

        // 只能为数字
        Route::pattern('id', '[0-9]+');
        Route::pattern('ids', '[0-9]+');

        // 以英文“,”号分隔且分隔后每个数据必须为数字，无误后返回数组
        Route::bind('manyid', function($value) {
            $data = explode(',', $value); // 将字符串转换为数组
            $data = array_filter($data); // 去除数组中的空值
            $data = array_values($data); // 重新排序数组键名
            abort_if(!$data, 404, '非法操作'); // 验证数组是否为空
            abort_if(Arrays::is_types($data, 'is_numeric'), 404, '存在非法ID'); // 验证数组中是否存在非数字键值
            return $data;
        });
    }

    // 扩展表单验证规则
    public static function FormRules()
    {
        // 扩展中国身份证验证规则
        Validator::extend('identitys', function($attribute, $value, $parameters) {
            return preg_match('/(^\d{15}$)|(^\d{17}(x|X|\d)$)/', $value);
        });

        // 扩展中国手机号码验证规则
        Validator::extend('phone', function($attribute, $value, $parameters) {
            return preg_match('/^1[0123456789]\d{9}$/', $value);
        });

        // 扩展验证内容是否只包含[空格、英文、数字]
        Validator::extend('english', function($attribute, $value, $parameters) {
            return preg_match('/^[ a-z0-9]+$/i', $value);
        });

        // 扩展密码验证,默认C等级
        Validator::extend('password', function($attribute, $value, $parameters) {
            switch (strtoupper(implode('', $parameters))){
                case 'A':
                    return preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*? ]).*$/', $value);
                    break; // 一级强度 至少8位,必须包含至少1个大写字母,1个小写字母,1个数字,1个特殊字符
                case 'B':
                    return preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,18}$/', $value);
                    break; // 二级强度 6至18位,必须包含字母和数字,不能出现特殊符号
                case 'C':
                    return preg_match('/^(?![^a-zA-Z]+$)(?!\D+$)/', $value);
                    break; // 三级强度 必须包含字母和数字
                default:
                    return preg_match('/^(?![^a-zA-Z]+$)(?!\D+$)/', $value);
                    break;
            }
        });
    }
}
