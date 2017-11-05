<?php

namespace core\yaecho;

class Controller 
{
    private $config; //配置

    /**
     * 载入配置文件
     */
    public function __construct()
    {
        $this->config = $GLOBALS['config'];
    }

    /**
     * 获取请求参数
     *
     * @param string $method post\get...
     * @param string $name 参数名称
     * @param string $default 默认值
     * @return array
     */
    public function request(string $method, string $name = null, string $default = null)
    {
        $data = array();
        switch (strtolower($method)) {
            case 'post':
                $data = $_POST;
                if (empty($data)) {
                    $data = json_decode(file_get_contents('php://input'), true);
                }
                break;
            case 'get':
                $data = $_GET;
                unset($data['r']);
                break;
        }
        if (!is_null($name)) {
            $data = isset($data[$name]) ? $data[$name] : (is_null($default) ? false : $default);
        }
        is_string($data) and $this->input_filter($data);
        is_array($data) and array_walk_recursive($data,[$this, 'input_filter']);
        return $data;
    }

    /**
     * 安全过滤
     *
     * @param [type] $value
     * @return void
     */
    private function input_filter(&$value){
	    // TODO 其他安全过滤

        // 过滤查询特殊字符
        if(preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i',$value)){
            $value .= ' ';
        }
    }

    /**
     * 返回json
     * 
     * @return void
     */
    public function response($data, $info = null, $is_error = false)
    {
        $this->sendHttpStatus(200);
        header('Content-type: application/json;charset=uft-8');
        $result = array();
        $result['code'] = $is_error? 1 : 0;
        $result['msg'] = $info ?: '请求成功';
        $result['data'] = $data;
        return json_encode($result);
    }

    // 发送Http状态信息
    protected function sendHttpStatus($code) {
        static $_status = array(
            // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',
            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily ',  // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
        );
        if(isset($_status[$code])) {
            header('HTTP/1.1 '.$code.' '.$_status[$code]);
            // 确保FastCGI模式下正常
            header('Status:'.$code.' '.$_status[$code]);
        }
    }

}