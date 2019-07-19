<?php
/**
 * Created by PhpStorm.
 * User: 15237
 * Date: 2019/7/9
 * Time: 11:33
 */
/*class Test_sdk extends PS_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_api_tree(){
        $params = [
            'api_method' => '/api/v1/service/a_api/get_tree'
        ];
        $this->load->library('console_lib');
        $res = $this->console_lib->http_request($params);
        echo "<pre>";
        var_dump($res);
    }
}*/
require_once __DIR__.'/Console_lib.php';

$params = [];
$params = $_POST;
if(!empty($_FILES)){
    $params['pic_file_apk'] = $_FILES['pic_file_apk'];
}
$params['access_token'] = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb25zb2xlIiwiYXVkIjoiY29uc29sZSIsImlhdCI6MTU2MzM2MzMxNywibmJmIjoxNTYzMzYzMzE3LCJleHAiOjE1NjM0NDk3MTcsImRhdGEiOnsidXNlcl9pZCI6IjMiLCJjb21wX2lkIjoiMSIsIm9yZ19ubyI6IjAwMSIsImRlcHRfbm8iOiJqc2IiLCJpc19hZG1pbiI6IjEiLCJ1c2VyX3R5cGUiOiIxIiwidXNlcl9ubyI6IjE1MjM3MTc5MTkzIiwidXNlcl9uYW1lIjoiXHU0ZTAxXHU2OGE2XHU2ZDliIiwicGhvbmUiOiIxNTIzNzE3OTE5MyIsIm1haWwiOiI3NDIwNTU1OTdAcXEuY29tIiwiYXZhdGFyIjpudWxsLCJzdGF0dXMiOiIxIiwic3lzdGVtX25vIjoiZ2d6eCIsInNpZCI6MCwicHJpdmlsZWdlcyI6WyIyMSIsIjIyIiwiMjQiLCIyNiJdfX0.lckBWNLcVdGDH0glJ3ePFNbtXmeFT1p3pZ8Fg6OJFoQ';

//$params = [
//    'api_method' => '/api/v1/base/area/get_tree',
//    'api_method' => '/api/v1/org/company/get_company',
//    'api_method' => '/api/v1/user/user/get_detail',
    /*'api_method' => '/api/v1/system/version/publish_version',
    'ver' => 'v1.0.1.20190711',
    'ver_content' => '这是一个SDK测试版本',
    'system_type' => 'web',*/
    /*'api_method' => '/api/v1/system/version/delete_version',
    'sys_ver_id' => '134',*/
//    'api_method' => '/api/v1/base/file/upload_file',
//    'pic_file_apk' => '154089447811919.png',
//
//    'access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb25zb2xlIiwiYXVkIjoiY29uc29sZSIsImlhdCI6MTU2MzI2NDM5MCwibmJmIjoxNTYzMjY0MzkwLCJleHAiOjE1NjMzNTA3OTAsImRhdGEiOnsidXNlcl9pZCI6IjMiLCJjb21wX2lkIjoiMSIsIm9yZ19ubyI6IjAwMSIsImRlcHRfbm8iOiJqc2IiLCJpc19hZG1pbiI6IjEiLCJ1c2VyX3R5cGUiOiIxIiwidXNlcl9ubyI6IjE1MjM3MTc5MTkzIiwidXNlcl9uYW1lIjoiXHU0ZTAxXHU2OGE2XHU2ZDliIiwicGhvbmUiOiIxNTIzNzE3OTE5MyIsIm1haWwiOiI3NDIwNTU1OTdAcXEuY29tIiwiYXZhdGFyIjpudWxsLCJzdGF0dXMiOiIxIiwic3lzdGVtX25vIjoiZ2d6eCIsInNpZCI6MCwicHJpdmlsZWdlcyI6WyIyMSIsIjIyIiwiMjQiLCIyNiJdfX0.xMaLXbVSB_JFYT8rlL4lnDJDE8bz3dCsowmPVfHbhFg',
//];

$console_lib = new Console_lib();
$res = $console_lib->http_request($params);
//echo "<pre>";
//var_dump($res);
echo $res;