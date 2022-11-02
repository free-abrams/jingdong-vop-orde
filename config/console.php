<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'cancelOverTimeOrder' => 'app\command\CancelOrder',
        'schedule' => 'app\command\Schedule'
    ],
];
