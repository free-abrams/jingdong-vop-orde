<?php
namespace app\common\lib\Upload\Qiniu;

use app\common\model\mysql\System\SystemConfig;
use Qiniu\Auth;

class Qiniu
{
    private static $config = [];

    protected static function auth(): Auth
    {
        $where[] = ['key', '=', 'qiniu'];
        self::$config = (new SystemConfig)->where($where)->value('value');

        return new Auth(self::$config['accessKey'], self::$config['secretKey']);
    }

    # 上传授权token
    public static function auto_token($folderPath = null): array
    {
        $token = self::auth()->uploadToken(self::$config['bucket'], $folderPath, self::$config['uploadExpires']);
        $data['token']       = $token;
        $data['visitPrefix'] = self::$config['visitPrefix'];
        $data['folderPath']  = $folderPath;
        $data['domain']   = self::$config['domain'];
        $data['realPath'] = self::$config['domain'].DIRECTORY_SEPARATOR.$folderPath;

        return $data;
    }

    # 对指定文件进行私有下载
    public static function private_url($url): string
    {
        return self::auth()->privateDownloadUrl($url, self::$config['privateDownloadExpires']);
    }
}
