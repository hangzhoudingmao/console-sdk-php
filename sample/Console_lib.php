<?php

use CONSOLE\ConsoleClient;
use CONSOLE\Core\ConsoleException;

require_once __DIR__.'/Config.php';
require_once __DIR__.'/../src/CONSOLE/ConsoleClient.php';
require_once __DIR__.'/../src/CONSOLE/Core/ConsoleException.php';
/**
 * Created by PhpStorm.
 * User: 15237
 * Date: 2019/7/9
 * Time: 10:40
 */
class Console_lib
{
    const authCode = Config::CONSOLE_AUTH_CODE;
    const domain = Config::CONSOLE_DOMAIN;
    const userNo = Config::CONSOLE_USER_NO;
    const password = Config::CONSOLE_PASSWORD;
    const loginType = Config::CONSOLE_LOGIN_TYPE;
    const systemNo = Config::CONSOLE_SYSTEM_NO;

    public $consoleClient;
    public $params;//实际使用是可直接实例化ConsoleClient类后，将该参数值传到http_curl_request方法中

    public function __construct()
    {
        $this->consoleClient = new ConsoleClient(self::authCode);
    }

    private function deal_parameters($params){
        $access_token = $params['access_token'] ?? '';
        if(array_key_exists('access_token', $params)){
            unset($params['access_token']);
        }

        $data = [
            'params' => $params,
            'user_no' => self::userNo,
            'password' => self::password,
            'login_type' => self::loginType,
            'system_no' => self::systemNo,
            'access_token' => $access_token,
        ];

        return $data;
    }

    public function http_request($params)
    {
        $data = $this->deal_parameters($params);

        try{
            $res = $this->consoleClient->http_curl_request(self::domain, $data);
            return $res;
        } catch(ConsoleException $e) {
//            printf(__FUNCTION__ . ": FAILED\n");
//            printf($e->getMessage() . "\n");
            return $e->getMessage();
        }
//        print(__FUNCTION__ . ": OK" . "\n");
    }
}