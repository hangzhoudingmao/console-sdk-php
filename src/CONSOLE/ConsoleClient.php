<?php

namespace CONSOLE;

use CONSOLE\Core\ConsoleConfig;
use CONSOLE\Core\ConsoleException;

require_once __DIR__.'/Core/ConsoleConfig.php';
require_once __DIR__.'/Core/ConsoleException.php';
/**
 * 1.签名 createSign
 * 2.请求 post json
 * 3.服务器接收接口开发及服务器验签
 * 4.授权码换取key
 * 5.key+用户名密码换取token
 * 6.token请求接口
 * 7.返回数据并处理
 */

/**
 * Created by PhpStorm.
 * User: 15237
 * Date: 2019/7/8
 * Time: 10:23
 */
class ConsoleClient
{
    private $auth_code;
    private $domain;
    private $api_name;
    private $url;
    private $new_data;

    private $user_no;
    private $password;
    private $login_type;
    private $system_no;

    private $key;
    private $token;

    public function __construct($auth_code)
    {
        $auth_code = trim($auth_code);

        if (empty($auth_code)){
            throw new ConsoleException("access key secret is empty");
        }

        $this->auth_code = $auth_code;
        $this->api_name = ConsoleConfig::API_NAME;
    }

    /**
     * 方法 createSign，对参数进行加密签名
     *
     * @param string $secret_key 秘钥
     * @param array $data 参数数组
     *
     * @return string $sign 签名
     *
     */
    protected function createSign($secret_key, $sign_data)
    {
        // 对数组的值按key排序
        ksort($sign_data);
        // 生成url的形式
        $params = http_build_query($sign_data);
        // 生成sign
        $sign = md5($params . $secret_key);
        return $sign;
    }

    /**
     * @param array $data 参数json对象
     */
    public function setData($data)
    {
        $data['timestamp'] = time();
        $data['auth_code'] = $this->auth_code;
        $data['domain'] = $this->domain;
        $data['sign'] = $this->createSign($this->auth_code, $data);
        return $data;
    }

    /**
     * 方法 http_post_request，模拟http post/get请求
     *
     * @param string $domain 域名
     * @param array $params 参数数组
     * @param boolean $is_https 是否https
     *
     * @return mixed $data 返回json格式的信息
     *
     */
    public function http_curl_request($domain, $params, $is_https=false){
        $this->validate($domain, $params, $is_https);

        if(!empty($this->token)){
            //不作处理
        }else if(array_key_exists('access_token', $params) && !empty($params['access_token'])){
            $this->token = $params['access_token'];
        }else{
            $token_key_data = $this->new_data;
            if(array_key_exists('pic_file_apk', $token_key_data['params'])){
                unset($token_key_data['params']['pic_file_apk']);
            }
            //通过授权码获取秘钥
            $this->get_key($token_key_data);
            //通过秘钥获取token
            $this->get_token($token_key_data);
        }
        $this->new_data['access_token'] = $this->token;

        /*$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$this->url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, $this->domain);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->new_data));
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);*/

        $data = $this->http_post_data_1($this->url, $this->new_data);
        return $data;
    }

    /**
     * 方法 validate，参数校验
     *
     * @param string $domain 域名
     * @param array $data 数组
     * @param boolean $is_https 是否https
     *
     */
    protected function validate($domain, $data, $is_https)
    {
        if (empty($domain)){
            throw new ConsoleException("domain is empty");
        }
        if(!is_array($data) || empty($data)){
            throw new ConsoleException("data should be given array or is empty");
        }
        /*if(empty($data['token']))
        {
            throw new ConsoleException("token is empty");
        }*/
        /*if(empty($data['params']['system_no']))
        {
            throw new ConsoleException("system_no is empty");
        }*/
        if(empty($data['params']['api_method'])){
            throw new ConsoleException("api_method is empty");
        }
        if(empty($data['user_no'])){
            throw new ConsoleException("user_no is empty");
        }
        if(empty($data['password'])){
            throw new ConsoleException("password is empty");
        }

        if(strtoupper(stripos(strtolower($domain), 'http')) === 'FALSE'){
            if($is_https){
                $this->domain = 'https://'.$domain;
            }else{
                $this->domain = 'http://'.$domain;
            }
        }else{
            $this->domain = $domain;
        }

        $this->url = $domain.$this->api_name;
        //$this->new_data = $this->setData($data);
        $data['domain'] = $this->domain;
        $this->user_no = $data['user_no'];
        $this->password = $data['password'];
        $this->login_type = $data['login_type'];
        $this->system_no = $data['system_no'];
        unset($data['user_no'], $data['password'], $data['login_type'], $data['system_no']);
        $this->new_data = $data;
    }

    /**
     * 方法 get_key，通过授权码获取key
     */
    public function get_key($params)
    {
        $params['params']['auth_code'] = $this->auth_code;
        $params['params']['system_no'] = $this->system_no;
        $params['params']['api_method'] = ConsoleConfig::GET_KEY;

        $key_res = $this->http_post_data_1($this->url, $params);
        $res_ary = json_decode($key_res, true);
        if($res_ary['code'] != 0){
            throw new ConsoleException("内部异常：".$res_ary['msg']);
        }
        $this->key = $res_ary['data']['key'];
    }

    /**
     * 方法 get_token，通过key获取token
     */
    public function get_token($params)
    {
        if(empty($this->key)){
            throw new ConsoleException('内部异常：key未生成，请重新请求！');
        }

        $params['key'] = $this->key;
        $params['params']['api_method'] = ConsoleConfig::GET_TOKEN;
        //$params['params']['user_no'] = $params['user_no'];
        //$params['params']['password'] = $params['password'];
        //$params['params']['login_type'] = $params['login_type'];
        $params['params']['user_no'] = $this->user_no;
        $params['params']['password'] = $this->password;
        $params['params']['login_type'] = $this->login_type;

        $user_res = $this->http_post_data_1($this->url, $params);
        $res_ary = json_decode($user_res, true);
        if($res_ary['code'] != 0){
            throw new ConsoleException("内部异常：".$res_ary['msg']);
        }
        $this->token = $res_ary['data']['access_token'];
        //$this->new_data['access_token'] = $this->token;
    }

    public function http_post_data_1($url, $data, $header='')
    {
       //初使化init方法
       $ch = curl_init();
       //指定URL
       curl_setopt($ch, CURLOPT_URL, $url);
       //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
       //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
       //curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
       //curl_setopt($ch, CURLOPT_REFERER, $this->domain);
       //设定请求后返回结果
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       //声明使用POST方式来进行发送
       curl_setopt($ch, CURLOPT_POST, 1);
       //发送什么数据呢
       curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
       //忽略证书
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       //忽略header头信息
       //curl_setopt($ch, CURLOPT_HEADER, false);
        if(!empty($header)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
       //设置超时时间
       //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
       //curl_setopt($ch, CURLOPT_TIMEOUT, 60);
       //发送请求
       $output = curl_exec($ch);
       //关闭curl
       curl_close($ch);
       //返回数据
       return $output;
    }

    function http_post_data_2($url, $data, $header='')
    {
        // 参数数组
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($ch, CURLOPT_HEADER, false);
        if(!empty($header)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }


}