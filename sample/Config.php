<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 15237
 * Date: 2019/7/8
 * Time: 10:07
 */
final class Config
{
    const CONSOLE_AUTH_CODE = '';//公共中心分配的授权码
    const CONSOLE_DOMAIN = 'http://dev_console_api.ihibuilding.cn';//请求的接口域名,正式环境为http://console_api.hibuilding.cn
    const CONSOLE_USER_NO = '';//用户账号
    const CONSOLE_PASSWORD = '';//用户密码
    const CONSOLE_LOGIN_TYPE = 1;//默认值，勿改
    const CONSOLE_SYSTEM_NO = 'ggzx';//用户系统编码，需向SDK提供方确认
}
