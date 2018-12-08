<?php

namespace Starrysea\Validate;

use Illuminate\Support\Facades\Request;

trait FormRequest
{
    // 是否终止并输出当前请求验证类名
    protected $showSource = false;

    // 是否终止并输出当前请求规则
    protected $showRule = false;

    function __construct(){
        if ($this->showSource === true){
            dd([
                'method' => $this->rulesmethod(true),
                'path'   => $this->rulespath(true),
                'route'  => $this->rulesroute(true),
            ]);
        }
    }

    /**
     * 获取聚合验证结果
     * 优先级：route > path > method > rules
     * @return array
     */
    public function combine()
    {
        $data = array_merge(
            $this->rules(),
            $this->rulesmethod(),
            $this->rulespath(),
            $this->rulesroute()
        );
        return $this->showRule === true ? dd($data) : $data;
    }

    /**
     * 根据请求类路径定义对应的验证规则
     * @param bool $returnName true返回规则方法名
     * @return array|string 规则|规则方法名
     */
    private function rulespath(bool $returnName = false)
    {
        $get = Request::getPathInfo();
        $get = str_replace('/',' ', $get);
        return $this->rulesReturn(__FUNCTION__ . ' ' . $get, $returnName);
    }

    /**
     * 根据请求路由名定义对应的验证规则
     * @param bool $returnName true返回规则方法名
     * @return array|string 规则|规则方法名
     */
    private function rulesroute(bool $returnName = false)
    {
        $get = '';

        if (!empty(Request::route()->action['as']))
            $get = __FUNCTION__ . ' ' . Request::route()->action['as'];

        return $this->rulesReturn($get, $returnName);
    }

    /**
     * 根据请求方式获得验证规则方法名
     * @param bool $returnName true返回规则方法名
     * @return array|string 规则|规则方法名
     */
    private function rulesmethod(bool $returnName = false)
    {
        return $this->rulesReturn('rules' . ucfirst(strtolower(Request::method())), $returnName);
    }

    /**
     * 获得返回数据
     * @param string $get
     * @param bool $returnName
     * @return array|string
     */
    private function rulesReturn(string $get, bool $returnName = false)
    {
        $get = camel_case($get);
        if ($returnName === true) return $get;
        return method_exists($this, $get) ? $this->$get() : [];
    }
}