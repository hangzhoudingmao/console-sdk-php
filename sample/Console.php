<?php

use CONSOLE\ConsoleClient;
use CONSOLE\Core\ConsoleConfig;
use CONSOLE\Core\ConsoleException;

/**
 * Created by PhpStorm.
 * User: 15237
 * Date: 2019/7/8
 * Time: 10:18
 */
if (is_file(__DIR__ . '/../autoload.php')) {
    require_once __DIR__ . '/../autoload.php';
}
if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}
require_once __DIR__ . '/Config.php';

class Console
{
    const accessSecretKey = Config::SECRET_KEY;
    const domain = Config::DOMAIN;
    const userNo = Config::USER_NO;
    const password = Config::PASSWORD;
    const api_name = ConsoleConfig::API_NAME;

    public $consoleClient;
    public $object;//实际使用是可直接实例化ConsoleClient类后，将该参数值传到http_curl_request方法中

    public function __construct()
    {
        $this->consoleClient = new ConsoleClient(self::accessSecretKey, self::api_name);
        $this->object = json_encode(['data'=>[
            'api_method' => '/api/v1/service/a_api/get_tree',
            'user_no' => self::userNo,
            'password' => self::password
        ]]);
    }

    public function http_request()
    {
        try{
            $res = $this->consoleClient->http_curl_request(self::domain, $this->object);
            return $res;
        } catch(ConsoleException $e) {
//            printf(__FUNCTION__ . ": FAILED\n");
//            printf($e->getMessage() . "\n");
            return $e->getMessage();
        }
//        print(__FUNCTION__ . ": OK" . "\n");
    }
}