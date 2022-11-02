<?php

namespace app;

use app\common\lib\ErrorCode;
use ParseError;

# 语法错误
use TypeError;

use app\common\exception\BusinessException;

// 自定义异常

use InvalidArgumentException;
use WeChat\Exceptions\InvalidResponseException;

// 参数错误

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\db\exception\PDOException;

// 数据库连接错误
use think\db\exception\DbException;

// 数据库模型访问错误，比如方法不存在

use think\exception\RouteNotFoundException;
use think\exception\ClassNotFoundException;
use think\exception\FuncNotFoundException;
use think\exception\FileException;

use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\exception\ErrorException;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport
        = [
            HttpException::class,
            HttpResponseException::class,
            ModelNotFoundException::class,
            DataNotFoundException::class,
            ValidateException::class,
        ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request $request
     * @param Throwable      $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 方法（控制器、路由、http请求）、资源（多媒体文件，如视频、文件）未匹配到，
        // 一旦在定义的路由规则中匹配不到，它就会直接去匹配控制器，但是因为在控制器中做了版本控制v1,v2这样的，所以它是无法获取对应控制器的
        // 所以都会直接走了HttpException的错误
        // 感觉好像也无所谓，反正是做api接口的，只不过这样就不好准确的提示信息了
        // 到底这个请求时控制器找不到呢？还是方法找不到？还是请求类型（get,post）不对？

        # 请求异常
        if ($e instanceof HttpException && $request->isAjax()) {
            $data = [
                'message'     => $e->getMessage(),
                'headers'     => $e->getHeaders(),
                'status_code' => $e->getStatusCode()
            ];
            return show(ErrorCode::$errors['sys_err_http'], $data);
        }

        # 控制器不存在
        if ($e instanceof ClassNotFoundException) {
            return show(ErrorCode::$errors['sys_err_module'], null);
        }

        # 方法不存在
        if ($e instanceof FuncNotFoundException) {
            return show(ErrorCode::$errors['sys_err_fun_res'], null);
        }

        # 路由不存在
        if (($e instanceof RouteNotFoundException) || ($e instanceof HttpException && $e->getStatusCode() === 404)) {
            return show(ErrorCode::$errors['sys_err_route_res'], null);
        }

        # 使用了错误的数据类型 或 缺失参数
        if ($e instanceof InvalidArgumentException) {

            $fileUrlArr = explode(DIRECTORY_SEPARATOR, $e->getFile());

            $data = [
                'message' => $e->getMessage(),
                'file'    => $fileUrlArr[count($fileUrlArr) - 1],
                'line'    => $e->getLine()
            ];

            return show(ErrorCode::$errors['sys_err_param'], $data);
        }

        # 参数验证错误
        if ($e instanceof ValidateException) {
            ErrorCode::$errors['sys_err_validate']['message'] = ErrorCode::$errors['sys_err_validate']['message'] .'：'.$e->getMessage();
            return show(ErrorCode::$errors['sys_err_validate'], null);
        }

        # 自定义添加的业务异常
        if ($e instanceof BusinessException) {
            return show(ErrorCode::$errors[$e->getMessage()], null);
        }

        # 微信错误
        if ($e instanceof InvalidResponseException) {
            return show(ErrorCode::$errors['sys_err_wx'], $e->getMessage());
        }

        // 3.语法错误
        if ($e instanceof ParseError || $e instanceof ErrorException) {
            $fileUrlArr = explode(DIRECTORY_SEPARATOR, $e->getFile());
            $data       = [
                'message' => $e->getMessage(),
                'file'    => $fileUrlArr[count($fileUrlArr) - 1],
                'line'    => $e->getLine()
            ];
            return show(ErrorCode::$errors['sys_err'], $data);
        }

        if ($e instanceof TypeError) {
            $fileUrlArr = explode(DIRECTORY_SEPARATOR, $e->getFile());
            $data       = [
                'message' => $e->getMessage(),
                'file'    => $fileUrlArr[count($fileUrlArr) - 1],
                'line'    => $e->getLine()
            ];
            return show(ErrorCode::$errors['sys_err'], $data);
        }

        // 4.数据库错误 $e instanceof PDOException ||
        if ($e instanceof DbException) {
            $fileUrlArr = explode(DIRECTORY_SEPARATOR, $e->getFile());
            $data       = [
                'message' => $e->getMessage(),
                'file'    => $fileUrlArr[count($fileUrlArr) - 1],
                'line'    => $e->getLine()
            ];
            return show(ErrorCode::$errors['sys_err'], $data);
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
