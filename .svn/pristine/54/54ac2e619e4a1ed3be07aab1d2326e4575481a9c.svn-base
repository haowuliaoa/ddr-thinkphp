<?php

namespace app\common\model;

use think\Cache;
use cache\Rediscache;
class Order extends Base
{
    /**
     * 根据order_id集获取记录数
     * @param int $order_ids
     * @param String $tag 点灯人  或  小步读书
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getNumsByOrderIds($order_ids = [], $tag = '点灯人')
    {
        $fields = [
            'image', 'price', 'date_available', 'viewed'
        ];
        $where['order_id'] = ['in', $order_ids];
        $where['store_name'] = $tag;
        $where['order_status_id'] = ['gt', 0];
        return self::where($where)->select()->count();
    }
}
