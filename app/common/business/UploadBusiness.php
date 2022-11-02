<?php

namespace app\common\business;


use app\common\lib\Upload\Qiniu\Qiniu;
use think\facade\Config;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
 * 七牛云封装类
 */
class UploadBusiness
{
    const object = [
        'health_mall',
        'unified_user'
    ];

    const type = [
        'image',
        'audio',
        'video'
    ];

    /**
     * @param string $field
     * @param string $dirname
     * @return 在七牛云上的文件名  方法：putFile();参数说明
     * @throws \Exception
     */
    public static function upload(string $field = '', string $dirname = '')
    {

        // 存储空间名称
        $bucket = Config::get('upload.qiniu.bucket');
        // 存储空间对应的域名
        $domain = Config::get('upload.qiniu.domain');
        // 用于签名的公钥 AK
        $accessKey = Config::get('upload.qiniu.accessKey');
        // 用于签名的私钥 SK
        $secretKey = Config::get('upload.qiniu.secretKey');
        $file = request()->file($field);

        if ($file) {
            // 临时文件路径
            $tmpName = $file->getRealPath();
            // 初始化鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传Token
            $token = $auth->uploadToken($bucket);
            // 上传管理类 构建UplaodManager对象
            $uploadMgr = new UploadManager();

            $ext = $file->getOriginalExtension();

            // 目录名
            if ($dirname != '') $dirname .= '/';
            // 随机文件名
            $path = $dirname . md5(microtime(true) . mt_rand(1, 1e9)) . '.' . $ext;
            $info = $uploadMgr->putFile($token, $path, $tmpName);
            // 上传到七牛云后的新名称
            return $info;
        } else {
            // throw new BusinessException('没有文件上传');
            return '没有文件上传';
        }
    }

    public function getJsUploadToken($param): array
    {
        $folder = [
            self::object[$param['object']],
            self::type[$param['type']],
            date('Y'),
            date('m'),
            date('d'),
            get_number_chang(20, 'MYT')
        ];
        $folder = implode(DIRECTORY_SEPARATOR, $folder);
        return Qiniu::auto_token($folder);
    }
}