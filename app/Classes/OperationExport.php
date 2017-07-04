<?php

/**
 * Created by PhpStorm.
 * User: hc
 * Date: 16/6/28
 * Time: 上午10:09
 */
namespace App\Classes;

/**
 * Class OperationExport
 * @package App
 * 从注释导出操作
 */
class OperationExport
{
    private $namespace;//命名空间
    private $controllerDir;//控制器目录

    public function __construct($controllerDir)
    {
        $this->controllerDir = $controllerDir;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function export()
    {
        $out = [];
        $files = glob($this->controllerDir . '/*Controller.php');
        foreach ($files as $f) {
            $tmp = [];
            $splits = explode('/', $f);
            $file_name = end($splits);//输出元素最后一个值
            $controller_name = substr($file_name, 0, -4);
            $rc = new \ReflectionClass($this->controllerName($controller_name));//
            $module = $this->getValueFromDoc($rc->getDocComment(), '@module');
            if (!$module) continue;
            $tmp['module'] = $module;
            $sort = null;//显示顺序
            $pos = strpos($module, '-');
            if ($pos) {
                $sort_str = substr($module, 0, $pos);
                if (is_numeric($sort_str)) {
                    $tmp['sort'] = (int)$sort_str;//获取序号
                    $tmp['module'] = substr($module, $pos + 1);//获取模块名称
                }
            }
            foreach ($rc->getMethods(\ReflectionMethod::IS_PUBLIC) as $v) {
                $method = $v->getName();
                $operation = $this->getValueFromDoc($v->getDocComment(), '@operation');
                if (!$operation) continue;
                $action = $controller_name . '@' . $method;
                $tmp['operation'][] = ['name' => $operation, 'action' => $action];
            }
            $out[] = $tmp;
        }
        return $out;
    }

    private function controllerName($name)
    {
        return $this->namespace ? $this->namespace . '\\' . $name : $name;
    }

    private function getValueFromDoc($doc, $key)
    {
        preg_match("#$key (\S*)#", $doc, $matches);
        return isset($matches[1]) ? $matches[1] : '';
    }
}