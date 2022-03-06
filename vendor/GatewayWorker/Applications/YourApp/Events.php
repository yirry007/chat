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
        //Gateway::sendToClient($client_id, "Hello $client_id\r\n");
        // 向所有人发送
        //Gateway::sendToAll("$client_id login\r\n");

        Gateway::sendToClient($client_id, json_encode([
            'type'=>'init',
            'client_id'=>$client_id,
        ]));
    }

   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
        // 向所有人发送
        //Gateway::sendToAll("$client_id said $message\r\n");

       $message_data = json_decode($message, true);

       if (!$message_data) {
           return false;
       }

       switch ($message_data['type']) {
           case 'bind':
               $fromid = $message_data['fromid'];
               Gateway::bindUid($client_id, $fromid);
               break;

           case 'say':
               $text = nl2br(htmlspecialchars($message_data['data']));
               $fromid = $message_data['fromid'];
               $toid = $message_data['toid'];
               $data = ['type'=>'text', 'data'=>$text, 'fromid'=>$fromid, 'toid'=>$toid, 'time'=>time()];

               if (Gateway::isUidOnline($toid)) {
                   $data['isread'] = 1;
                   Gateway::sendToUid($toid, json_encode($data));
               } else {
                   $data['isread'] = 0;
               }

               $data['type'] = 'save';
               Gateway::sendToUid($fromid, json_encode($data));
               break;

           case 'say_img':
               $toid = $message_data['toid'];
               $fromid = $message_data['fromid'];
               $url = $message_data['data'];
               $data = array();
               $data['type'] = 'say_img';
               $data['fromid'] = $fromid;
               $data['toid'] = $toid;
               $data['url'] = $url;
               Gateway::sendToUid($toid, json_encode($data));
               break;

           case 'online':
               $fromid = $message_data['fromid'];
               $toid = $message_data['toid'];
               $state = Gateway::isUidOnline($toid);
               Gateway::sendToUid($fromid, json_encode(['type'=>'online', 'state'=>$state]));
               break;
       }
   }

   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       // 向所有人发送
       GateWay::sendToAll("$client_id logout\r\n");
   }
}
