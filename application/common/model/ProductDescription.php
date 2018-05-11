<?php

namespace app\common\model;

use think\Cache;
use cache\Rediscache;
class ProductDescription extends Base
{
    /**
     * 获取商品名称
     * @param array $product_ids
     * @param array $fields
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getNameByProductIds($product_ids = [], $fields = ['product_id, name'], $limit = 5)
    {
        if(is_array($product_ids)){
            $where['product_id'] = ['in', $product_ids];
        }else{
            $where['product_id'] = $product_ids;
        }
        $where['language_id'] = 2;//1英语 2中文；我们只做中文
        if($limit){
            return self::where($where)->field($fields)->limit(5)->order('product_id', 'desc')->select()->toArray();
        }else{
            return self::where($where)->field($fields)->select()->toArray();
        }
    }
}
