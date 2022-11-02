<?php
// 应用公共文件
use app\common\exception\BusinessException;
use think\facade\Request;
use think\response\Json;

# 数据返回
function show($status, $data = [], $httpStatus = 200): Json
{
    $result = [
        "code"    => $status['code'],
        "message" => $status['message'],
        "result"  => $data
    ];

    return json($result, $httpStatus);
}

# 时间
function time_tran($the_time)
{
    $now_time  = date("Y-m-d H:i:s", time());
    $now_time  = strtotime($now_time);
    $show_time = strtotime($the_time);
    $dur       = $now_time - $show_time;
    if ($dur < 0) {
        return $the_time;
    } else {
        if ($dur < 60) {
            return $dur . '秒前';
        } else {
            if ($dur < 3600) {
                return floor($dur / 60) . '分钟前';
            } else {
                if ($dur < 86400) {
                    return floor($dur / 3600) . '小时前';
                } else {
                    if ($dur < 259200) {//3天内
                        return floor($dur / 86400) . '天前';
                    } else {
                        return $the_time;
                    }
                }
            }
        }
    }
}

# uuid
function create_uuid($prefix = ""): string
{
    $chars = md5(uniqid(mt_rand(), true));
    $uuid = substr($chars, 0, 8) . '-'
        . substr($chars, 8, 4) . '-'
        . substr($chars, 12, 4) . '-'
        . substr($chars, 16, 4) . '-'
        . substr($chars, 20, 12);
    return $prefix . $uuid;
}

/**
 *  随机生成代码
 * @param     $length
 * @param int $type
 * @return string
 */
function get_string($length, int $type = 1): string
{
    $chars = null;

    switch ($type) {
        case 1:
            $chars = '0123456789';
            break;
        case 2:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            break;
        case 3:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqistuvwxyz0123456789';
            break;
        case 4:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqistuvwxyz0123456789+-=[]{}|\/<> !@#$%^&*:?.,';
            break;
    }

    $return = '';
    for ($i = 0; $i < $length; $i++) {
        $return .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $return;
}

/**
 * 随机生成订单号
 * @param null $fix
 * @return string|void [type] [返回值是一维数组]
 */
function get_number_chang($length = 6, $fix = null)
{
    $string = get_string($length);
    return $fix . date('Ymd', time()) . $string;
}

/**
 * 绑定验证器类
 */
function param_validate()
{
    $name = app('http')->getName();

    $namespace  = 'app\\' . $name . '\validate';
    $controller = Request::controller();
    $action     = Request::action();


    $className = $namespace . '\\' . class_basename($controller) . 'Validate';
    $validate  = validate($className);

    # 检测是否不存在
    if (!$validate->hasScene($action)) {
        throw new BusinessException('sys_err_validate_is_empty');
    }

    $validate->scene(Request::action())->check(Request::param());
}

/**
 * 加密
 * @param string $password
 * @return false|string|null
 */
function password_encode(string $password)
{
    return password_hash($password.config('app.salt'), PASSWORD_BCRYPT);
}

/**
 * 解密
 * @param string $password
 * @param $hash
 * @return bool
 */
function password_decode(string $password, $hash): bool
{
    return password_verify($password.config('app.salt'), $hash);
}

/**
 * Send a GET requst using cURL
 * @param string $url
 * @param array  $get
 * @param array  $options
 * @param bool   $judge
 * @return bool|string
 */
function requestGet(string $url, $get = array(), $options = array(),$judge = FALSE) {

    $defaults = array(
        CURLOPT_URL            => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
        CURLOPT_HEADER         => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => $judge === FALSE ? FALSE : TRUE,
        CURLOPT_SSL_VERIFYHOST => $judge === FALSE ? FALSE : 2,
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch) ) {
        $result = curl_error($ch);
    }
    curl_close($ch);
    return $result;
}

/**
 * Send a POST requst using cURL
 * @param       $url
 * @param array $post
 * @param array $options
 * @param int   $iTimeout
 * @param bool  $judge
 * @return bool|string
 */
function requestPost($url, $post = array(), $options = array(), $iTimeout = 30 ,$judge = TRUE) {

    $defaults = array(
        CURLOPT_POST           => 1,
        CURLOPT_HEADER         => 0,
        CURLOPT_URL            => $url,
        CURLOPT_FRESH_CONNECT  => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE   => 1,
        CURLOPT_TIMEOUT        => $iTimeout,
        CURLOPT_POSTFIELDS     => $post,
        CURLOPT_SSL_VERIFYPEER => $judge === FALSE ? FALSE : TRUE,
        CURLOPT_SSL_VERIFYHOST => $judge === FALSE ? FALSE : 2,
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults) );

    if( ! $result = curl_exec($ch) ){
        $result = curl_error($ch);
    }
    curl_close($ch);
    return $result;
}
