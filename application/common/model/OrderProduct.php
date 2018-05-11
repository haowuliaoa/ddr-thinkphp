<?php

namespace app\common\model;

use think\Cache;
use cache\Rediscache;
class OrderProduct extends Base
{
    /**
     * 获取这个产品归属的订单id集
     * @param int $product_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOrderIdByProductid($product_id = 0)
    {
        $fields = ['order_id'];
        $where['product_id'] = $product_id;
        return self::where($where)->field($fields)->select()->toArray();
    }
}
