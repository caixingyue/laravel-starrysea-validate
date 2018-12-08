<?php

namespace Starrysea\Validate\Tests;

class FormRequestGatherTest
{
    // 展现构筑规则类名
    protected $showSource = true;

    // 展现当前请求验证的所有规则
    protected $showRule = true;

    // 该规则任何时候都生效, 但是优先级最低, 可被其它规则覆盖
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