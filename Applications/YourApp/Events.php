<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
use GlobalData\Client;

require_once __DIR__ . '/../../src/Client.php';



class Events
{

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */


    public static function onConnect($client_id)
    {


        // 向当前client_id发送数据
        Gateway::sendToClient($client_id, "Hello $client_id");

        $global = new Client('127.0.0.1:2207');
        $global->add('mes','diyici');
        $var = $global->mes;

        echo ($var);
        //当有web端连接客户连接进来后,给他们发送所有车辆的当前位置.

        if (strlen($var) > 8) {
            Gateway::sendToClient($client_id, $var);

        }
        // 向所有人发送
        // Gateway::sendToAll("$client_id login");



    }

    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage($client_id, $message)
    {
        // 向所有人发送
        Gateway::sendToAll($message);
        $global = new Client('127.0.0.1:2207');

        switch ($_SERVER['GATEWAY_PORT']) {
            case '8881':

                $global->mes = $message;
        }
        echo($message . "\n");
        echo("globl" . $global->mes . "\n");
    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id)
    {
        // 向所有人发送
        GateWay::sendToAll("$client_id logout");
    }



}

