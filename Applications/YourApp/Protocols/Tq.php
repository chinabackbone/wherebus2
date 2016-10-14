<?php
/**
 * Created by PhpStorm.
 * User: haoqiang
 * Date: 15-12-30
 * Time: 上午9:18
 */
//use \Workerman\Connection\TcpConnection;
namespace Protocols;
class Tq
{
    /**
     * 检查包的完整性
     * 如果能够得到包长，则返回包的长度，否则返回0继续等待数据
     * @param string $buffer
     */
    public static function input($buffer)
    {
        // 由于没有包头，无法预先知道包长，不能无限制的接收数据，
        // 所以需要判断当前接收的数据是否超过限定值

        if(strlen($buffer) < 9)
        {
            // 不够10字节，返回0继续等待数据
            return 0;
        }
        // 获得换行字符"\n"位置
        $pos_s = strpos($buffer, chr(42));
        $po_e = strpos($buffer,chr(35));
        // 返回包长，包长包含 头部数据长度+包体长度
        //$total_len = base_convert($buffer, 10, 10);
        return $po_e-$pos_s+1;
    }

    /**
     * 打包，当向客户端发送数据的时候会自动调用
     * @param string $buffer
     * @return string
     */
    public static function encode($buffer)
    {
        // 加上换行
        return $buffer;
//        return "111";
    }

    /**
     * 解包，当接收到的数据字节数等于input返回的值（大于0的值）自动调用
     * 并传递给onMessage回调函数的$data参数
     * @param string $buffer
     * @return string
     */
    public static function decode($buffer)
    {
        // 去掉换行
        return trim($buffer);
    }
}
