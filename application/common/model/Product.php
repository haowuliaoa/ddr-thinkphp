<?php

namespace app\common\model;

use think\Cache;
use cache\Rediscache;
class Product extends Base
{
    /**
     * 获取分类集下的产品id集
     * @param array $category_ids
     * @param array $fields
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getByProductId($product_id = 0)
    {
        $fields = [
            'image', 'price', 'date_available', 'viewed'
        ];
        $where['product_id'] = $product_id;
        return self::where($where)->field($fields)->find();
    }

    public static function getProductList($data){
        $res = [
            'more_href',
            'thumb',
            'name',
            'manufacturer',
            'rating',
            'rating',
            'special',
            'price_num',
            'price',
'special_num',
            'price',
            'special',
        ];
    }
}
