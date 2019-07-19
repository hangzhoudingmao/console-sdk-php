<?php
/**
 * Created by PhpStorm.
 * User: 15237
 * Date: 2019/7/10
 * Time: 10:33
 */

$data=array(
    "name" => "Lei",
    "msg" => "Are you OK?"
);

//$url = 'http://dev_console_api.ihibuilding.cn/testRespond.php';
$url = "http://dev_console_api.ihibuilding.cn/api/v1/http_curl/receive_request";
//$url = "http://dev_console_api.ihibuilding.cn/api/v1/service/a_api/get_tree";

$res = http_curl_post_1($url, $data);
//http_curl_post_2($url, $data);
echo "<pre>";
var_dump(json_decode($res, true));

function http_curl_post_1($url, $data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_URL, );
    curl_setopt($ch, CURLOPT_POST, 1);
    //The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_POSTFIELDS , http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
//    echo $output;

    return $output;

    curl_close($ch);
}

function http_curl_post_2($url, $data)
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
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //设置超时时间
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    //发送请求
    $output = curl_exec($ch);
    echo $output;
    //关闭curl
    curl_close($ch);

    //返回数据
    return $output;
}

