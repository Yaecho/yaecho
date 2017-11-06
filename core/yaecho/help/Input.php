<?php

namespace core\yaecho\help;

class Input
{
    /**
     * 获取请求参数
     *
     * @param string $method post\get...
     * @param string $name 参数名称
     * @param string $default 默认值
     * @return array
     */
    public function get(string $method, string $name = null, string $default = null)
    {
        $data = array();
        switch (strtolower($method)) {
            case 'delete':
            case 'put':
            case 'patch':
            case 'post':
                $data = json_decode(file_get_contents('php://input'), true);
                break;
            case 'get':
                $data = $_GET;
                unset($data['r']);
                break;
        }
        if (!is_null($name)) {
            $data = isset($data[$name]) ? $data[$name] : (is_null($default) ? false : $default);
        }
        return $data;
    }
}
